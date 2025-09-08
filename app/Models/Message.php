<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message', 'senderId', 'reciverId']; 
    public function sender()
    {
        return $this->belongsTo(User::class, 'senderId');
    } 
    public function reciver()
    {
        return $this->belongsTo(User::class, 'reciverId');
    }
}
