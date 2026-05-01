<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyContact extends Model
{
    protected $fillable = [
        'user_id',
        'family_profile_id',
        'name',
        'contact_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function familyProfile()
    {
        return $this->belongsTo(FamilyProfile::class);
    }
}