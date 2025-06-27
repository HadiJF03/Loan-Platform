<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pledge extends Model
{
    use HasFactory;
    protected $fillable= [
        'user_id',
        'item_type',
        'description',
        'images',
        'requested_amount',
        'collateral_duration',
        'repayment_terms',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
    
}
