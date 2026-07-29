<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_no',
        'purchase_date',
        'reference_no',
        'supplier_id',
        'currency_id',
        'exchange_rate',
        'sub_total',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_method',
        'notes',
        'status',
        'user_id',
        'payment_status',
    ];

    protected static function booted()
    {
        static::saving(function ($purchase) {
            $grandTotal = (float) $purchase->grand_total;
            $paidAmount = (float) $purchase->paid_amount;
            
            if ($paidAmount <= 0) {
                $purchase->payment_status = 'Unpaid';
            } elseif ($paidAmount >= $grandTotal) {
                $purchase->payment_status = 'Paid';
            } else {
                $purchase->payment_status = 'Partial';
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Currency::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
