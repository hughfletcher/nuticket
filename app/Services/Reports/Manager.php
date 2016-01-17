<?php namespace App\Services\Reports;

use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    public function getDefaultDriver()
    {
        return 'default';
    }

    public function createDefaultDriver()
    {
        return new DefaultSource($this->app);
    }
}
