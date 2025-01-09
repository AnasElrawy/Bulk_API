<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMSLog extends Model
{
    //
    protected $fillable = [
        'sender_name',
        'message',
        'phone_number',
        'status',
    ];
    
    
}
