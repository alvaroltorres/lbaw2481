<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Chat.php
class Chat extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'chat_id';
    public $timestamps = false;

    protected $fillable = ['is_private', 'created_at'];

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id', 'chat_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'chat_id');
    }
}

