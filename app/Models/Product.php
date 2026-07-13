<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'barcode',
        'brand_id',
        'main_category_id',
        'sub_category_id',
        'unit_name',
        'unit_code',
        'purchase_price',
        'selling_price',
        'tax_percentage',
        'discount_price_amount',
        'minimum_stock_alert',
        'image',
        'gallery',
        'short_description',
        'full_description',
        'status',
        'manufacturer',
        'model_number',
        'part_number',
        'warranty',
        'color',
        'weight',
        'country_of_origin',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function purchaseReturnItems(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function saleReturnItems(): HasMany
    {
        return $this->hasMany(SaleReturnItem::class);
    }

    /**
     * Determine if the product has any completed transaction logged.
     */
    public function hasTransactions(): bool
    {
        return $this->purchaseItems()->whereHas('purchase', fn($q) => $q->where('status', 'Completed'))->exists()
            || $this->saleItems()->whereHas('sale', fn($q) => $q->where('status', 'Completed'))->exists()
            || $this->purchaseReturnItems()->exists()
            || $this->saleReturnItems()->exists()
            || $this->stockAdjustments()->exists();
    }
}
