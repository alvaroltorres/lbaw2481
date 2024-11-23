<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    protected $table = 'Bid';

    protected $fillable = ['auction_id', 'user_id', 'price'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public $timestamps = false;

    protected $primaryKey = 'bid_id';
}
