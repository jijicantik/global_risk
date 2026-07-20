<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
        'country_code',
        'country_name'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
