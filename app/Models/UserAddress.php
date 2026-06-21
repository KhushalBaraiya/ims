<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'mobile_country_code',
        'mobile_no',
        'email',
        'house_no',
        'landmark',
        'locality_area',
        'pincode',
        'city',
        'state',
        'country',
        'address_type',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
