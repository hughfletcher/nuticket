<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

	protected $table = 'config';

    public $timestamps = false;

	protected $fillable = [
        'key',
        'value'
    ];

    public function getValueAttribute($value)
    {
        if(in_array($value, ['false']) || $value === null)
        {
            return false;
        }

        if(in_array($value, ['true']))
        {
        	return true;
        }

        return $value;
    }

}
