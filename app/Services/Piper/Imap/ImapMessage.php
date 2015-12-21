<?php namespace App\Services\Piper\Imap;

use App\Services\Piper\MessageInterface;
use App\Services\Piper\Message;

class ImapMessage extends Message implements MessageInterface
{
    protected function getMessageBody()
    {
        return $this->message->getMessageBody();
    }

    public function getSubject()
    {
        return $this->message->getSubject();
    }

    protected function getFromName()
    {
        return $this->message->getAddresses('from')['name'];
    }

    protected function getFromEmail()
    {
        return $this->message->getAddresses('from')['address'];
    }

    public function delete()
    {
        return $this->message->delete();
    }

    public function move($destination)
    {
        $this->message->moveToMailBox($destination);
    }
}
