<?php namespace App\Services\Piper\Pipes;

use App\Policies\TicketPolicy;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Log\Writer as Log;
use App\Services\Piper\MessageInterface;

class TicketPipe
{
    use DispatchesJobs;

    public function __construct(TicketPolicy $policy, Validator $validator, Log $log)
    {
        $this->policy = $policy;
        $this->validator = $validator;
        $this->log = $log;
    }

    public function create(MessageInterface $message)
    {
        $attrs = $this->createAttrs($message);
        $check = $this->validator->make($attrs, $this->policy->storeRules());

        if ($check->fails()) {
             $this->log->debug('Email to ticket request failed validation.', $check->errors()->all());
            return null;
        }

        $this->log->debug('Email to ticket request dispatched for creation.', $attrs);
        return $this->dispatchFrom('App\Jobs\TicketCreateJob', collect($attrs));
    }

    public function update(MessageInterface $message)
    {
        $attrs = $this->updateAttrs($message);
        $check = $this->validator->make($attrs, $this->policy->updateRules());

        if ($check->fails()) {
             $this->log->debug('Email to ticket update request failed validation.', $check->errors()->all());
            return null;
        }

        $this->log->debug('Email to ticket request dispatched for update.', $attrs);
        return $this->dispatchFrom('App\Jobs\TicketUpdateJob', collect($attrs));
    }

    public function createAttrs(MessageInterface $message)
    {
        return array_merge([
            'assigned_id' => $message->getAssignedId(),
            'dept_id' => config('system.default.dept'),
            'org_id' => $message->getOrgId() ? $message->getOrgId() : $message->getUser()->org_id,
            'title' => $message->getSubject(),
            'body' => $message->getSkinny(),
            'user_id' => $message->getUserId(),
            'priority' => $message->getTags()->get('priority')
        ], $this->attrs($message));
    }

    public function updateAttrs(MessageInterface $message)
    {
        $attrs = ['reason' => $message->getSkinny(), 'ticket_id' => $message->getTicketId()];
        $tags = $message->getTags();

        if ($assigned_id = $message->getAssignedId()) {
            $attrs['assigned_id'] = $assigned_id;
        }

        if ($tags->has('user')) {
            $attrs['user_id'] = $message->getUserId();
        }

        if ($tags->has('priority')) {
            $attrs['priority'] = $tags->get('priority');
        }

        if ($tags->has('org')) {
            $attrs['org_id'] = $message->getOrgId();
        }

        return array_merge($attrs, $this->attrs($message));
    }

    public function attrs(MessageInterface $message)
    {
        return [
            'auth_id' => $message->getAuthorId(),
            'source' => 'mail',
            // 'defer_event' => true
        ];
    }


}
