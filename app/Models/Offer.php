<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'pledge_id',
        'user_id',
        'offer_amount',
        'duration',
        'terms',
        'status',
        'is_amendment',
        'parent_offer_id',
    ];

    // Relationships
    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function amendments()
    {
        return $this->hasMany(Offer::class, 'parent_offer_id');
    }

    public function parentOffer()
    {
        return $this->belongsTo(Offer::class, 'parent_offer_id');
    }

    public function history()
    {
        return $this->hasMany(Offer_History::class);
    }
}
