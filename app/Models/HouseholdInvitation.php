<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseholdInvitation extends Model
{
    protected $table = 'household_invitations';

    protected $fillable = [
        'family_profile_id',
        'invited_user_id',
        'invited_by',
        'status',
    ];

    public function familyProfile()
    {
        return $this->belongsTo(FamilyProfile::class);
    }

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }

    public function invitee()
    {
        return $this->invitedUser();
    }

    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}