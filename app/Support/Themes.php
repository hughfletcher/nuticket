<?php namespace App\Support;

use Symfony\Component\Finder\Finder;

class Themes
{
	public function __construct(Finder $finder)
	{
		$this->finder = $finder;
	}

	public function all()
	{	
		
	}

	public function config($theme)
	{
		return json_decode(file_get_contents(public_path() . '/themes/' . $theme . '/theme.json'), true);
	}
}