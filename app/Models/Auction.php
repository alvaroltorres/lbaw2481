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
    protected $table = 'auction'; // Specify the exact table name

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'auction_id'; // Specify the primary key if different from 'id'

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
        'title',
        'location',
        'status',
        // Add other fillable fields as necessary
    ];

    // Define any relationships or additional methods here
}
