<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\HouseholdInvitation;

class FamilyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'household_name',
        'address',
        'household_code',
        'latitude',
        'longitude',
        'disaster_risks',
    ];

    protected $casts = [
        'disaster_risks' => 'array',
    ];

    // Auto-generate clan code on creation
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($profile) {
            $profile->household_code = strtoupper(Str::random(6));
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function invitations()
    {
        return $this->hasMany(HouseholdInvitation::class);
    }
}