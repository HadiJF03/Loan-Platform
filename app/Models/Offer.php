<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pledge_id',
        'offer_amount',
        'status',
        'parent_id',
        'commission',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function parentOffer()
    {
        return $this->belongsTo(Offer::class, 'parent_id');
    }

    public function childOffers()
    {
        return $this->hasMany(Offer::class, 'parent_id');
    }
    public function amendments()
    {
        return $this->hasMany(Offer::class, 'parent_offer_id');
    }
}
