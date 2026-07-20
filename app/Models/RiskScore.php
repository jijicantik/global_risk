<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'weather_score',
        'inflation_score',
        'currency_score',
        'news_sentiment_score',
        'total_score',
        'risk_level'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
