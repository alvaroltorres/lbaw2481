<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'Bid'; // Use the exact table name, case-sensitive and quoted in your database

    protected $primaryKey = 'bid_id';

    public $timestamps = true;

    const CREATED_AT = 'time'; // Map 'time' column to 'created_at'

    const UPDATED_AT = null; // No 'updated_at' column in the table

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
