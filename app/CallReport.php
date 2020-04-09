<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallReport extends Model
{
    protected $fillable = [
        'call_event',
        'event',
        'request',
        'call_id'
    ];
}
