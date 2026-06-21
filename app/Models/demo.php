<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class demo extends Model
{
    /** @use HasFactory<\Database\Factories\DemoFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'gender',
        'address',
        'status',
    ];
}
