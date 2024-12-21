<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    protected $table = 'chatparticipant';
    protected $primaryKey = 'chat_participant_id';
    public $timestamps = false;

    protected $fillable = ['chat_id', 'user_id', 'joined_at'];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
