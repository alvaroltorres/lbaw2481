<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'User';

    protected $primaryKey = 'user_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'nif',
        'email',
        'password_hash',
        'is_admin',
        'is_blocked',
        'two_factor_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_admin' => 'boolean',
        'is_enterprise' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the cards for a user.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    // Column used for password.
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // App\Models\User.php

    public function auctions()
    {
        return $this->hasMany(Auction::class, 'user_id', 'user_id');
    }


    /**
     * Get the bids placed by the user.
     */
    public function bids()
    {
        return $this->hasMany(Bid::class, 'user_id', 'user_id');
    }

    /**
     * Set the user's password.
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password_hash'] = Hash::make($password);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }


    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')
            ->whereNull('read_at');
    }

    public function followedAuctions()
    {
        return $this->belongsToMany(Auction::class, 'follow_auctions', 'user_id', 'auction_id');
    }

}
