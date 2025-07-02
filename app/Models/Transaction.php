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
        'start_date',
        'end_date',
        'collateral_status',
        'payment_status',
        'commission',
        'payment_method',
        'delivery_method',
        'collateral_confirmed_by_pledger',
        'collateral_confirmed_by_pledgee',
        'payment_confirmed_by_pledger',
        'payment_confirmed_by_pledgee',
    ];

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
