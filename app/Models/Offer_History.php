<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer_History extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $table = 'offers_history';
    protected $fillable = [
        'offer_id',
        'user_id',
        'change_description',
        'snapshot',
        'changed_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'changed_at' => 'datetime',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}