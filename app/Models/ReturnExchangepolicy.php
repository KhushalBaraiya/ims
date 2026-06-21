<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnExchangepolicy extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'product_id', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
