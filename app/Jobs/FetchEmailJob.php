<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Email;
use Fetch\Server;
use EmailReplyParser\Parser\EmailParser;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Requests\TicketStoreRequest;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Auth\AuthManager;
use Fetch\Message;
use Illuminate\Log\Writer as Log;

class FetchEmailJob extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $email;

    protected $request;

    protected $log;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;

        $this->request = collect([
            'auth_id' => null,
            'user_id' => null,
            'title' => null,
            'body' => null,
            'source' => 'mail',
            'status' => 'new',
            'dept_id' => config('system.defaultdept'),
            'assigned_id' => null,
            'hours' => null,
            'time_at' => null,
            'priority' => 3,
            'reply' => null,
            'comment' => null,
            'display_name' => null,
            'email' => null
        ]);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailParser $parser, Validator $validator, AuthManager $auth, Log $log)
    {
        $server = $this->getServer();

        $messages = $server->getMessages();
        $log->debug('Fetching email messages', ['emails_id' => $this->email->id, 'server' => $server->getServerString(), 'message_count' => $server->numMessages()]);

        foreach ($messages as $message) {

            if (!$this->parseAddresses($message, $auth, $log)) {
                continue;
            }

            $this->request->put('title', $message->getSubject());

            $body = $parser->parse($message->getMessageBody());
            $fragment = current($body->getFragments());
            $this->request->put('body', $fragment->getContent());

            $this->processEmail($message);
            $log->debug('Email processed for ticket creation validation.', $this->request->toArray());

            $check = $validator->make($this->request->toArray(), TicketStoreRequest::$rules);

            if ($check->fails()) {
                $log->debug('Email to ticket request failed validation.', $check->errors()->all());
                continue;
            }

            $log->debug('Email to ticket request dispatched for creation.', $this->request->toArray());
            $this->dispatchFrom('App\Jobs\CreateTicketJob', $this->request);



        }
    }

    protected function newServer($serverPath, $port = 143, $service = 'imap')
    {
        return new Server($serverPath, $port, $service);
    }

    public function getServer()
    {
        $server = $this->newServer($this->email->mail_host, $this->email->mail_port, $this->email->mail_protocol);
        $server->setAuthentication($this->email->userid, $this->email->userpass);
        $server->setFlag('novalidate-cert');

        if ($this->email->mail_ssl) { $server->setFlag('ssl'); }

        return $server;
    }

    public function parseAddresses(Message $message, AuthManager $auth, Log $log)
    {
        $from = $message->getAddresses('from');
        // $auth = $user->findBy('email', $from['address'], ['id']);
        $user = $auth->getProvider()->retrieveByCredentials(['email' => $from['address']]);

        if (!$user) {

            if (config('mail.acceptunknown')) {
                $this->request->put('display_name', $from['name']);
                $this->request->put('email', $from['email']);
                return true;
            }

            $log->notice('Email to ticket rejected - unknown user', $from);
            return false;
        }

        $this->request->put('user_id', $user->id);
        $this->request->put('auth_id', $user->id);
        return true;
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
}
