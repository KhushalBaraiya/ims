<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'image',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(MainCategory::class, 'category_id');
    }
}
