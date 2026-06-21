<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'status'];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
