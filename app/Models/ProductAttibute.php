<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttibute extends Model
{
    use SoftDeletes;

    protected $table = 'product_attibutes';

    protected $fillable = [
        'product_id',
        'attribute_key',
        'attribute_value',
    ];

    protected $casts = [
        'attribute_value' => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeColors($query)
    {
        return $query->where('attribute_key', 'Color');
    }

    public function scopeSizes($query)
    {
        return $query->where('attribute_key', 'Size');
    }
}
