<?php namespace App\Services\Piper\Pipes;

use App\Policies\TicketActionPolicy;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Log\Writer as Log;
use App\Services\Piper\MessageInterface;
use Illuminate\Support\Collection;

class ActionPipe
{
    use DispatchesJobs;

    public function __construct(TicketActionPolicy $policy, Validator $validator, Log $log)
    {
        $this->policy = $policy;
        $this->validator = $validator;
        $this->log = $log;
    }

    public function create(MessageInterface $message)
    {
        return $this->build($message);
    }

    public function build(MessageInterface $message)
    {
        $tags = $message->getTags();
        $body = $this->getBody($message);
        $attributes = $this->attrs($message);
        // $actions = collect();

        $actionables = $tags->only(['reply', 'comment', 'closed', 'resolved', 'open']);

        foreach ($actionables as $type => $action) {
            $attrs = collect($attributes)->put('type', $type);

            if ($body) {
                $attrs->put('body', $body);
                $body = null;
            } else {
                continue;
            }

            if ($tags->has('hours')) {
                $attrs->put('hours', $tags->get('hours'));
                $tags->forget('hours');
            }

            if (!$this->validate($attrs)) {
                continue;
            }
            $this->dispatchFrom('App\Jobs\ActionCreateJob', $attrs);
        }

        if ($this->assign($message, $body)) {
            $body = null;
        }



        if ($body) {
            $attrs = collect($attributes)->merge(['body' => $body, 'hours' => $tags->get('hours')]);
            // $attrs;
            $this->dispatchFrom('App\Jobs\ActionCreateJob', $attrs);
        }

        // return $actions;
    }

    public function attrs(MessageInterface $message)
    {
        return [
            'source' => 'mail',
            'type' => 'reply',
            // 'defer_event' => true,
            'user_id' => $message->getAuthorId(),
            'ticket_id' => $message->getTicketId() ? $message->getTicketId() : $message->getCreatedTicketId()
        ];
    }

    public function assign(MessageInterface $message, $body = null)
    {
        if (!$assigned_id = $message->getAssignedId()) {
            return null;
        }

        $attrs = collect($this->attrs($message));

        return $this->dispatchFrom(
            'App\Jobs\ActionCreateJob',
            $attrs->merge(['type' => 'assign', 'assigned_id' => $assigned_id, 'body' => $body])
        );
    }

    public function validate(Collection $attrs)
    {
        $check = $this->validator->make($attrs->toArray(), $this->policy->createRules());

        if ($check->fails()) {
            $this->log->debug('Action create by email failed validation.', $check->errors()->all());
            return false;
        }

        return true;
    }

    public function getBody(MessageInterface $message)
    {
        if (!$message->getTicketId() || !$message->getTags()->only('user', 'priority')->isEmpty()) {
            return;
        }

        return $message->getSkinny();
    }
}
