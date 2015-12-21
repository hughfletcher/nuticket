<?php namespace App\Services\Piper;

use Illuminate\Support\Manager as BaseManager;
use App\Email;

class Manager extends BaseManager
{
    public function pipe(Email $email)
    {
        $this->email = $email;
        return $this->driver($email->mail_protocol);
    }

    public function getDefaultDriver()
    {
        return 'imap';
    }

    public function createImapDriver()
    {
        $server = new Imap\ImapServer($this->email, $this->app);
        return $server->messages();
    }
}
