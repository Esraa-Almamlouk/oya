<?php

namespace App\Models;

use App\Enums\Category;
use App\Enums\Currency;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'category',
        'currency',
        'bank',
        'phone',
    ];

    protected $casts = [
        'category' => Category::class,
        'currency' => Currency::class,
    ];
}
