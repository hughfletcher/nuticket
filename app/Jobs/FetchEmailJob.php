<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use App\Services\Piper\Piper;
use Fetch\Server;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Requests\TicketStoreRequest;
use App\Http\Requests\TicketUpdateRequest;
use App\Http\Requests\ActionCreateRequest;
use Validator;
use Log;
use Fetch\Message;
use App\Services\EmailParser;
use App\Contracts\Repositories\UserInterface;
use Illuminate\Support\Collection;
use App\Events\TicketCreatedEvent;
use App\Events\ActionCreatedEvent;

class FetchEmailJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    public function handle(Piper $piper)
    {
        $piper->fetch($this->email);
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle1(EmailParser $parser, UserInterface $user)
    {
        $server = $this->getServer();

        $messages = $server->getMessages();
        Log::debug('Fetching email messages', ['emails_id' => $this->email->id, 'server' => $server->getServerString(), 'message_count' => $server->numMessages()]);

        foreach ($messages as $message) {
            $data = $parser->parse($message);
            $data = $data->merge(['source' => 'mail', 'defer_event' => true]);

            //User
            $data = $this->anonymousUser($data, $user);

            $data = $this->getAssigned($data, $user);

            // create ticket
            $data = $this->createTicket($data);

            $actions = $this->buildActions($data);

            if ($data->has('ticket')) {
                event(new TicketCreatedEvent($data->get('ticket')));
            } else {
                event(new ActionCreatedEvent($actions));
            }

            $this->processEmail($message);
        }
    }

    public function anonymousUser(Collection $data, UserInterface $user)
    {
        // $data = clone $data;
        if (!$data->has('auth_id') && config('mail.acceptunknown')) {
            $auth = $user->create(['display_name' => $data->get('name'), 'email' => $data->get('email')]);
            $data->put('auth_id', $auth->id);
        }

        return $data;
    }

    public function newServer($serverPath, $port = 143, $service = 'imap')
    {
        return new Server($serverPath, $port, $service);
    }

    public function getServer()
    {
        $server = $this->newServer($this->email->mail_host, $this->email->mail_port, $this->email->mail_protocol);
        $server->setAuthentication($this->email->userid, $this->email->userpass);
        $server->setFlag('novalidate-cert');

        if ($this->email->mail_ssl) {
            $server->setFlag('ssl');
        }

        return $server;
    }

    public function buildActions(Collection $data)
    {
        // $data = clone $data;
        // $data = $data->merge(['user_id' => $data->get('auth_id'), '_user_id' => $data->get('user_id')]);

        $actions = collect();
        $actionables = $data->only(['reply', 'comment', 'closed', 'resolved', 'open']);

        foreach ($actionables as $type => $action) {
            $attrs = $data->only(['ticket_id', 'source', 'defer_event', 'user_id', 'hours'])
                ->merge(['type' => $type, 'user_id' => $data->get('auth_id')]);

            if ($data->get($type) != '') {
                // specified reply action

                $attrs->put('body', $data->get($type));
            } else {
                // assume main body is for this action
                $attrs->put('body', $data->get('body'));
                // remove $data['body']
                $data->forget('body');
            }
            // remove $data['reply']
            $data->forget($action);
            // add any hours and remove
            if ($data->has('hours')) {
                $attrs->put('hours', $data->get('hours'));
                $data->forget('hours');
            }
            //validate
            // var_dump($attrs);
            if ($this->validateAction($attrs)) {
                $actions->push($this->dispatchFrom('App\Jobs\ActionCreateJob', $attrs));
            }

        }

        if ($assign = $this->buildAssignedAction($data)) {
            $actions->push($assign);
            $data->forget('body');
        }

        if ($edit = $this->createEditAction($data)) {
            $actions->push($edit);
            $data->forget('body');
        }

        //if we went through all that and still have a body then just a reply
        if ($data->has('body')) {

            $attrs = $data->only(['ticket_id', 'source', 'defer_event', 'body', 'hours'])
                ->merge(['type' => 'reply', 'user_id' => $data->get('auth_id')]);
            if ($this->validateAction($attrs)) {
                $actions->push($this->dispatchFrom('App\Jobs\ActionCreateJob', $attrs));
            }
        }

        return $actions;
    }

    public function validateAction(Collection $attrs)
    {
        $check = Validator::make($attrs->toArray(), ActionCreateRequest::$rules);

        if ($check->fails()) {
            Log::debug('Action create by email failed validation.', $check->errors()->all());
            return false;
        }

        return true;
    }

    public function createEditAction($data)
    {
        // $data = clone $data;

        if (!$data->has('priority') && !$data->has('user') && !$data('assigned_id')) {
            return false;
        }

        // $only = ['ticket_id', 'auth_id', 'user_id', 'source', 'defer_event', 'priority'];
        // if ($data->has('body')) {
        //     $only[] = 'body';
        // }

        $attrs = $data->only(['ticket_id', 'auth_id', 'user_id', 'source', 'defer_event', 'priority', 'assigned_id'])
            ->merge(['type' => 'edit', 'reason' => $data->get('body')]);

        $check = Validator::make($attrs->toArray(), TicketUpdateRequest::$rules);
        if ($check->fails()) {
            Log::debug('Email edit action failed validation.', $check->errors()->all());
            return false;
        }
        return $this->dispatchFrom('App\Jobs\TicketUpdateJob', $attrs);
    }

    public function getDept(Collection $data)
    {
        return $data->has('dept') ? $data->get('dept') : config('system.defaultdept');
    }

    public function createTicket(Collection $data)
    {
        if ($data->has('ticket_id')) {
            return $data;
        }

        $ticket = $this->dispatchTicket($data);

        $data->forget(['assign', 'priority', 'claim', 'user_id', 'body', 'title', 'user']);

        if (!$ticket) {
            return null;
        }

        return $data->merge(['ticket' => $ticket, 'ticket_id' => $ticket->id]);
    }

    public function buildTicket(Collection $data)
    {
        $data = clone $data;
        $merge = [
            'dept_id' => $this->getDept($data),
            'priority' => $data->has('priority') ? $data->get('priority') : 3
        ];

        return $data->merge($merge)
            ->only(['user_id', 'auth_id', 'title', 'body', 'source', 'assigned_id', 'dept_id', 'defer_event', 'priority']);

    }

    public function dispatchTicket(Collection $data)
    {
        $data = clone $data;
        $ticket = $this->buildTicket($data);
        $check = Validator::make($ticket->toArray(), TicketStoreRequest::$rules);

        if ($check->fails()) {
            Log::debug('Email to ticket request failed validation.', $check->errors()->all());
            return false;
        }

        Log::debug('Email to ticket request dispatched for creation.', $data->toArray());
        return $this->dispatchFrom('App\Jobs\TicketCreateJob', $ticket);

    }

    public function getAssigned(Collection $data, UserInterface $user)
    {
        $data = clone $data;
        if ($data->has('claim')) {
            return $data->put('assigned_id', $data->get('auth_id'));
        }

        if ($data->has('assigned')) {

            $user = $user->findWhere(['username' => $data->get('assigned'), 'is_staff' => true], ['id']);
            return $data->put('assigned_id', $user->first()->id);
        }

        return $data;
    }

    public function processEmail(Message $message)
    {
        if ($this->email->mail_delete) {
            $message->delete();
        }

        if ($this->email->mail_archivefolder) {
            $message->moveToMailBox($this->email->mail_archivefolder);
        }
    }

    public function createAssignedAction()
    {

    }

    public function getData()
    {
        return $this->data;
    }
}
