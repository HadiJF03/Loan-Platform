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

    public function latestAmendment()
    {
        return $this->amendments()->orderByDesc('created_at')->first();
    }

    public function rootOffer()
    {
        return $this->parentOffer ? $this->parentOffer->rootOffer() : $this;
    }
    public function pledgeeOriginalOffer()
    {
        $pledgeOwnerId = $this->pledge->user_id;

        $current = $this;
        while ($current->parentOffer && $current->parentOffer->user_id !== $pledgeOwnerId) {
            $current = $current->parentOffer;
        }

        return $current->user_id !== $pledgeOwnerId ? $current : null;
    }
}
