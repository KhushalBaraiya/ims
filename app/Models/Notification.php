<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'message', 'is_read', 'type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
