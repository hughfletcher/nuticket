<?php namespace App\Services\Piper\Pipes;

use App\Contracts\Repositories\UserInterface;
use Illuminate\Auth\AuthManager;

class UserPipe
{

    public function __construct(AuthManager $auth, UserInterface $user)
    {
        $this->auth = $auth->driver();
        $this->user = $user;
    }

    public function findOrCreate($display_name, $email)
    {
        $user = $this->findByEmail($email);

        if (!$user) {

            if (config('mail.acceptunknown')) {
                return $this->user->create(compact($display_name, $email));
            }

            // $log->notice('Email to ticket rejected - unknown user', $from);
            return null;
        }

        return $user;
    }

    public function findByEmail($email)
    {
        return $this->find(['email' => $email]);
    }

    public function findByUserName($username)
    {
        return $this->find(['username' => $username]);
    }

    public function find($attrs)
    {
        if (!$user = $this->auth->getProvider()->retrieveByCredentials($attrs)) {
            return null;
        }

        return $user;
    }
}
