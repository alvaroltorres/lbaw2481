<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
    public $timestamps = false;

    protected $fillable = ['rated_user_id','rater_user_id','transaction_id','score','comment','rating_time'];

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id', 'user_id');
    }

    public function raterUser()
    {
        return $this->belongsTo(User::class, 'rater_user_id', 'user_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
