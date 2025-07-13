<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mobile_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    // Optional helpers for roles
    public function isAdmin()
    {
        return $this->role === 'root';
    }

    public function isPledger()
    {
        return $this->role === 'pledger';
    }

    public function isPledgee()
    {
        return $this->role === 'pledgee';
    }
}
