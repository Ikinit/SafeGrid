<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoBagItems extends Model
{

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'is_packed',
        'nutritional_info',
        'expiry_date',
    ];

    protected $casts = [
        'is_packed' => 'boolean',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
