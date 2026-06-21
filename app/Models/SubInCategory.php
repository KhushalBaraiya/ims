<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubInCategory extends Model
{
     use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'status',
    ];
    public function category()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
