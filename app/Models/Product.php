<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id',
        'subcategory_id',
        'brand_id',
        'sub_in_categories_id',
        'description',
        'product_details',
        'fabric',
        'age_group',
        'care_instructions',
        'clothing_features',
        'neckline',
        'sleeve_length_type',
        'original_price',
        'price',
        'total_price',
        'discount_percent',
        'images',
        'rating',
        'quantity',
        'status',
        'is_favourite',
    ];

    protected $casts = [
        'original_price'   => 'decimal:2',
        'price'            => 'decimal:2',
        'total_price'      => 'decimal:2',
        'discount_percent' => 'integer',
        'rating'           => 'decimal:1',
        'quantity'         => 'integer',
        'is_favourite'     => 'boolean',
        'images'           => 'array',
        'deleted_at'       => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(MainCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function subInCategory()
    {
        return $this->belongsTo(SubInCategory::class, 'sub_in_categories_id');
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(WishList::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductAttribute::class)->where('attribute_key', 'Color');
    }

    public function sizes()
    {
        return $this->hasMany(ProductAttribute::class)->where('attribute_key', 'Size');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Dynamically computed average rating from reviews
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Discount amount in currency
    public function getDiscountAmountAttribute(): float
    {
        return round($this->original_price - $this->price, 2);
    }
}
