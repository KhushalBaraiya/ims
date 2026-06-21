<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeleteAccount extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'status'];

    protected $dates = ['deleted_at'];
}
