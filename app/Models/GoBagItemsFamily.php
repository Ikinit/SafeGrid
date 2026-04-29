<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoBagItemsFamily extends Model
{
    use HasFactory;

    protected $table = 'go_bag_items_family';

    protected $fillable = [
        'family_profile_id',
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

    public function familyProfile()
    {
        return $this->belongsTo(FamilyProfile::class);
    }
}
