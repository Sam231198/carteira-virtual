<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transation extends Model
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'message'
    ];

    protected $casts = [
        'amount' => 'float',
        'wallet_id' => 'integer',
        'wallet_transfer_id' => 'integer'
    ];
}
