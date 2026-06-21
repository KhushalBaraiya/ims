<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'quantity',
        'reason',
        'return_date',
        'return_type',
        'refund_amount',
        'status',
        'admin_note'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
