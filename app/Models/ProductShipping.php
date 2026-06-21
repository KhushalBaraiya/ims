<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductShipping extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'delivery_time',
        'charge',
        'free_above',
        'cod_available',
        'status',
    ];

    protected $casts = [
        'charge'        => 'float',
        'free_above'    => 'float',
        'cod_available' => 'boolean',
    ];
}
