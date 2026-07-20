<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LexiconWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'type' // positive, negative
    ];
}
