<?php namespace App\Http\Composers;

use App\Contracts\Repositories\EmailInterface;

class SettingsEmailsComposer {

	public function __construct(EmailInterface $emails) {
        $this->emails = $emails;
	}

    public function compose($view)
    {
        $view->with('emails', $this->emails->all(['id', 'name', 'email']));
        $view->with('mtas', $this->emails->findAllBy('smtp_active', 1, ['id', 'name', 'email']));

    }

}
