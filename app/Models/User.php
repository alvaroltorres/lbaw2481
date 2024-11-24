<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'User';
    protected $primaryKey = 'user_id';

    // Don't add created_at and updated_at timestamps in the database.
    public $timestamps = false;

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
        'is_enterprise',
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
        'password_hash' => 'hashed', // Utilize a funcionalidade do Laravel para hashing seguro.
    ];

    /**
     * Get the cards for the user.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Column used for password authentication.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
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
     * Automatically hash the password when it's set.
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password_hash'] = Hash::make($password);
    }
}
