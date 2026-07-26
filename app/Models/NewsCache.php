<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    use HasFactory;

    protected $table = 'news_cache';

    protected $fillable = [
        'country_code',
        'title',
        'description',
        'content',
        'url',
        'image_url',
        'source',
        'published_at',
        'sentiment_positive',
        'sentiment_negative',
        'sentiment_label',
        'category'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
