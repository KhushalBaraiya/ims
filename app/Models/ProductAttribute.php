<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    protected $table = 'product_attibutes'; // keep existing typo in table name

    protected $fillable = [
        'product_id',
        'attribute_key',
        'attribute_value',
    ];

    protected $casts = [
        'attribute_value' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
