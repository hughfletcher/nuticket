<?php namespace App\Services\Piper;

use App\Services\Piper\MessageInterface;
use App\Services\Piper\Pipes\TicketPipe;
use App\Services\Piper\Pipes\ActionPipe;
use App\Email;
use Fetch\Message;

class Piper
{
    public function __construct(Manager $manager, TicketPipe $ticket, ActionPipe $action)
    {
        $this->manager = $manager;
        $this->ticket = $ticket;
        $this->action = $action;
    }

    public function fetch(Email $email)
    {
        $messages = $this->manager->pipe($email);

        foreach ($messages as $message) {

            if (!$message->getAuthorId()) {
                continue;
            }

            if (!$message->getTicketId()) {
                $this->ticket->create($message);
            } else {
                $this->action->create($message);
            }

            if (!$message->getTags()->only(['user', 'priority', 'org'])->isEmpty() && $message->getTicketId()) {
                $this->ticket->update($message);
            }



            $this->clean($email, $message);
        }
    }

    public function clean(Email $email, MessageInterface $message)
    {
        if ($email->mail_delete) {
            $message->delete();
        }

        if ($email->mail_archivefolder) {
            $message->move($email->mail_archivefolder);
        }
    }
}
