<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotline extends Model
{
    protected $fillable = [
        'name',
        'contact_number',
        'location',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}