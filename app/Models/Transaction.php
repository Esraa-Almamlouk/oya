<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'reference',
        'account_id',
        'date',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'attachment',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
