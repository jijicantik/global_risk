<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'year',
        'gdp',
        'inflation',
        'currency_rate',
        'risk_score'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
