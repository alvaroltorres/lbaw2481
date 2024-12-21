<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = ['buyer_id','auction_id','payment_method_id','value','created_at','payment_deadline','status'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id', 'auction_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'transaction_id', 'transaction_id');
    }
}
