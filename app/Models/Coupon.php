<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'product_id',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'minimum_order_amount',
        'maximum_discount_value',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
