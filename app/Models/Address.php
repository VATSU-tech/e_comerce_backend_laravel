<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'country',
        'city',
        'street',
        'postal_code',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
