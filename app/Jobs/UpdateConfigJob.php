<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Repositories\ConfigInterface as Config;

class UpdateConfigJob extends Job implements SelfHandling
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $keys, $convert_underscores = false)
    {
        $this->keys = $keys;
        $this->convert = $convert_underscores;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Config $config)
    {
        foreach ($this->keys as $key => $value) {
            if ($this->convert) {
                $key = str_replace('_', '.', $key);
            }

            $type = 'string';
            if ($default = config($key)) {
                $type = gettype($default);
            }
            
            settype($value, $type);

            if ($default === $value) {
                $config->delete($key);
            } else {
                $config->store($key, serialize([$value]));
            }

            
        }
    }
}
