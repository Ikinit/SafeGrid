<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = [
        'family_profile_id',
        'user_id',
        'role',
        'is_owner',
        'latitude',
        'longitude',
        'location_sharing',
    ];

    protected $casts = [
        'is_owner' => 'boolean',
        'location_sharing' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function familyProfile()
    {
        return $this->belongsTo(FamilyProfile::class);
    }

    public function roles()
    {
    return $this->hasMany(MemberRole::class);
    }
}