<?php namespace App\Services\Piper\Imap;

use Illuminate\Foundation\Application;
use Fetch\Server;
use Fetch\Message;
use App\Email;

class ImapServer
{
    public function __construct(Email $email, Application $app)
    {
        $this->email = $email;
        $this->app = $app;
    }

    protected function newServer($serverPath, $port = 143, $service = 'imap')
    {
        return new Server($serverPath, $port, $service);
    }

    public function server()
    {
        $server = $this->newServer($this->email->mail_host, $this->email->mail_port, $this->email->mail_protocol);
        $server->setAuthentication($this->email->userid, $this->email->userpass);
        $server->setFlag('novalidate-cert');

        if ($this->email->mail_ssl) {
            $server->setFlag('ssl');
        }

        return $server;
    }

    public function create(Message $fetch)
    {
        $message = $this->app->make('App\Services\Piper\Imap\ImapMessage');
        $message->set($fetch);
        return $message;
    }

    public function messages()
    {
        $messages = collect();
        foreach ($this->server()->getMessages() as $message) {
            $messages->push($this->create($message));
        }

        return $messages;
    }
}
