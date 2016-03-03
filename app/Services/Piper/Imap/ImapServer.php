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

    public function newServer($serverPath, $port = 143, $service = 'imap')
    {
        return new Server($serverPath, $port, $service);
    }

    private function server()
    {
        $server = $this->newServer($this->email->mail_host, $this->email->mail_port, $this->email->mail_protocol);
        $server->setAuthentication($this->email->userid, $this->email->userpass);
        $server->setFlag('novalidate-cert');

        if ($this->email->mail_ssl) {
            $server->setFlag('ssl');
        }

        return $server;
    }

    private function create(Message $fetch)
    {
        $message = $this->app->make('App\Services\Piper\Imap\ImapMessage');
        $message->set($fetch);
        return $message;
    }

    public function messages()
    {
        $messages = collect();

        try {
            $fetch = $this->server()->getMessages();
        } catch (ErrorException $e) {
            lg('notice', $e->getMessage(), [$this->email->except('userpass')->toArray()]);
            return $messages;
        }

        foreach ($fetch as $message) {
            $messages->push($this->create($message));
        }

        return $messages;
    }
}
