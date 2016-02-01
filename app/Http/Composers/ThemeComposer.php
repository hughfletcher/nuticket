<?php namespace App\Http\Composers;

use App\Support\Theme;

class ThemeComposer {

	public function __construct(Theme $theme) {
        $this->theme = $theme;
	}

    public function compose($view)
    {
        $view->with('themes', $this->theme->all());

    }

}