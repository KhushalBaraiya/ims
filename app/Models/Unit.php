<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'short_name',
        'base_unit',
        'operator',
        'operation_value',
    ];

    public function parentUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }

    public function childUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'base_unit');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
