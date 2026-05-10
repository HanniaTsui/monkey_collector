<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'sizes', 'colors', 'images', 'stock', 'stock_por_talla', 'is_active', 'categoria', 'expires_at',
    ];

    protected $casts = [
        'sizes'          => 'array',
        'colors'         => 'array',
        'images'         => 'array',
        'stock_por_talla'=> 'array',
        'is_active'      => 'boolean',
        'price'          => 'float',
        'expires_at'     => 'datetime',
    ];
}
