<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_member_id',
        'event_name',
        'description',
    ];

    public function familyMember()
    {
        return $this->belongsTo(FamilyMember::class);
    }
}
