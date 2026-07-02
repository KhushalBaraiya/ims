<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'customer_id',
        'sales_person_id',
        'sub_total',
        'tax_amount',
        'discount_amount',
        'shipping_amount',
        'grand_total',
        'paid_amount',
        'due_amount',
        'payment_status',
        'payment_method',
        'discount_type',
        'discount_value',
        'tax_percentage',
        'notes',
        'status',
        'user_id',
    ];

    /**
     * Auto-compute payment_status whenever the model is saved.
     */
    protected static function booted(): void
    {
        static::saving(function (Sale $sale) {
            $grandTotal = (float) $sale->grand_total;
            $paidAmount = (float) $sale->paid_amount;

            if ($paidAmount <= 0) {
                $sale->payment_status = 'Unpaid';
            } elseif ($paidAmount >= $grandTotal) {
                $sale->payment_status = 'Paid';
            } else {
                $sale->payment_status = 'Partial';
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salesPerson(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_person_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturn::class);
    }
}
