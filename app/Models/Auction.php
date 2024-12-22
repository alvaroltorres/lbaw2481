<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'Auction'; // Specify the exact table name

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'auction_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // Set to false if your table doesn't have 'created_at' and 'updated_at'

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'starting_price',
        'reserve_price',
        'current_price',
        'minimum_bid_increment',
        'description',
        'starting_date',
        'ending_date',
        'created_at',
        'updated_at',
        'title',
        'location',
        'status',
        'winner_id',
        'image',
    ];
    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'auction_user', 'auction_id', 'user_id');
    }


    public function bids()
    {
        return $this->hasMany(Bid::class, 'auction_id', 'auction_id');
    }

    // App\Models\Auction.php

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function isExpired(): bool
    {
        return $this->ending_date->isPast();
    }
}
