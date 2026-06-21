<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubFaq extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'faq_id',
        'question',
        'answer',
        'status'
    ];
 public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_id');
    }
}