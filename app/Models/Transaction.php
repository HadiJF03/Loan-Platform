<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'pledge_id',
        'offer_id',
        'collateral_confirmed_by_pledger',
        'collateral_confirmed_by_pledgee',
        'payment_confirmed_by_pledger',
        'payment_confirmed_by_pledgee',
        'commission',
        'payment_status',
    ];

    protected $casts = [
        'collateral_confirmed_by_pledger' => 'boolean',
        'collateral_confirmed_by_pledgee' => 'boolean',
        'payment_confirmed_by_pledger' => 'boolean',
        'payment_confirmed_by_pledgee' => 'boolean',
        'commission' => 'decimal:2',
    ];

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    // Optional helpers
    public function pledger()
    {
        return $this->pledge?->user();
    }

    public function pledgee()
    {
        return $this->offer?->user();
    }
}
