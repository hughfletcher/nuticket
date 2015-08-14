<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

	protected $table = 'config';

	protected $fillable = [
        'enviroment', 
        'key',
        'value'
    ];

    public function getValueAttribute($value)
    {
        if(in_array($value, ['0', 'false']) || $value === null) 
        {
            return false;
        }

        if(in_array($value, ['1', 'true'])) 
        {
        	return true;
        }

        return $value;
    }

}
 