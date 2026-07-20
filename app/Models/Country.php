<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'latitude',
        'longitude',
        'gdp',
        'inflation',
        'population',
        'currency_code',
        'currency_name',
        'region',
        'language'
    ];

    public function metrics()
    {
        return $this->hasMany(CountryMetric::class, 'country_code', 'code');
    }

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class, 'country_code', 'code');
    }

    public function news()
    {
        return $this->hasMany(NewsCache::class, 'country_code', 'code');
    }
}
