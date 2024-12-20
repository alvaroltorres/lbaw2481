<?php
// app/Models/Chat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';
    protected $primaryKey = 'chat_id';
    public $timestamps = false;

    protected $fillable = ['auction_id', 'is_private', 'created_at'];

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id', 'chat_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'chat_id');
    }
}
