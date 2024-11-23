<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'bid';

    protected $primaryKey = 'bid_id';

    public $timestamps = true;

    const CREATED_AT = 'time';

    const UPDATED_AT = null;

    protected $fillable = ['auction_id', 'user_id', 'price'];

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id', 'auction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
