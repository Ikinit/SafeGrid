<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'is_admin',
        'active_family_profile_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // All households this user belongs to
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }

    // All family profiles this user is part of
    public function familyProfiles()
    {
        return $this->hasManyThrough(
            FamilyProfile::class,
            FamilyMember::class,
            'user_id',
            'id',
            'id',
            'family_profile_id'
        );
    }

    // The currently active household
    public function activeFamilyProfile()
    {
        return $this->belongsTo(FamilyProfile::class, 'active_family_profile_id');
    }

    // Get the current active member record
    public function activeMember()
    {
        if (!$this->active_family_profile_id) return null;

        return $this->familyMembers()
                    ->where('family_profile_id', $this->active_family_profile_id)
                    ->first();
    }

    // Pending household invitations
    public function pendingInvitations()
    {
        return $this->hasMany(HouseholdInvitation::class, 'invited_user_id')
                    ->where('status', 'pending');
    }
}