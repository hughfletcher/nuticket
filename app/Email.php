<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $table = 'emails';

    protected $casts = [
        'autoresp' => 'boolean',
        'mail_active' => 'boolean',
        'mail_ssl' => 'boolean',
        'mail_port' => 'integer',
        'mail_delete' => 'boolean',
        'smtp_active' => 'boolean',
        'smtp_secure' => 'boolean',
        'smtp_auth' => 'boolean'
    ];

    protected $guarded = ['id'];

    protected $dates = ['created_at', 'updated_at', 'mail_lasterror', 'mail_lastfetch'];
}
