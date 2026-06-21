<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'offer_name',
        'offer_code',
        'offer_type',
        'discount_value',
        'offer_value',
        'start_date',
        'end_date',
        'is_active',
        'current_usage_count',
        'status',
    ];
}
