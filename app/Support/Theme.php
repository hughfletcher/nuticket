<?php namespace App\Support;

class Theme
{
	public function all()
	{	
		$themes = collect();
		foreach (scandir(public_path() . '/themes') as $dir) {
			if (is_dir(public_path() . '/themes/' . $dir) && is_file(public_path() . '/themes/' . $dir . '/theme.json')) {
				$themes->put($dir, $this->config($dir));
			}

		}

		return $themes;
	}

	public function config($theme)
	{
		return json_decode(file_get_contents(public_path() . '/themes/' . $theme . '/theme.json'), true);
	}
}