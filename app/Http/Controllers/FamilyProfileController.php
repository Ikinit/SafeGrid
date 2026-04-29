<?php

namespace App\Http\Controllers;

use App\Models\HouseholdInvitation;
use App\Models\FamilyMember;
use App\Models\FamilyProfile;
use App\Models\User;
use Illuminate\Http\Request;

class FamilyProfileController extends Controller
{
    public function onboarding()
    {
        $invitations = auth()->user()->pendingInvitations()->with('familyProfile')->get();
        $households  = auth()->user()->familyProfiles;
        return view('family.onboarding', compact('invitations', 'households'));
    }

    public function index()
    {
        $user       = auth()->user();
        $profile    = $user->activeFamilyProfile->load('members.user');
        $households = $user->familyProfiles;
        return view('family.index', compact('profile', 'households'));
    }

    // Switch active household
    public function switchHousehold(FamilyProfile $profile)
    {
        $user = auth()->user();

        $isMember = $user->familyMembers()
                         ->where('family_profile_id', $profile->id)
                         ->exists();

        if (!$isMember) {
            abort(403, 'You are not a member of this household.');
        }

        $user->update(['active_family_profile_id' => $profile->id]);

        return redirect()->route('family.index')
                         ->with('success', 'Switched to ' . $profile->household_name);
    }

    // Create a new household
    public function createHousehold(Request $request)
    {
        $request->validate([
            'household_name' => ['required', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:255'],
            'disaster_risks' => ['nullable', 'array'],
        ]);

        $profile = FamilyProfile::create([
            'user_id'        => auth()->id(),
            'household_name' => $request->household_name,
            'address'        => $request->address,
            'disaster_risks' => $request->disaster_risks,
        ]);

        FamilyMember::create([
            'family_profile_id' => $profile->id,
            'user_id'           => auth()->id(),
            'role'              => 'Owner',
            'is_owner'          => true,
        ]);

        auth()->user()->update(['active_family_profile_id' => $profile->id]);

        return redirect()->route('family.index')
                         ->with('success', 'Household created successfully!');
    }

    // Join a household via code
    public function joinHousehold(Request $request)
    {
        $request->validate([
            'household_code' => ['required', 'string', 'size:6'],
        ]);

        $profile = FamilyProfile::where('household_code', strtoupper($request->household_code))->first();

        if (!$profile) {
            return back()->withErrors(['household_code' => 'Household code not found.']);
        }

        $alreadyMember = FamilyMember::where('family_profile_id', $profile->id)
                                     ->where('user_id', auth()->id())
                                     ->exists();
        if ($alreadyMember) {
            return back()->withErrors(['household_code' => 'You are already in this household.']);
        }

        FamilyMember::create([
            'family_profile_id' => $profile->id,
            'user_id'           => auth()->id(),
            'role'              => 'Member',
            'is_owner'          => false,
        ]);

        auth()->user()->update(['active_family_profile_id' => $profile->id]);

        return redirect()->route('family.index')
                         ->with('success', 'Joined household successfully!');
    }

    // Invite a member by username
    public function inviteMember(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
        ]);

        $invitedUser = User::where('username', $request->username)->first();

        if (!$invitedUser) {
            return back()->withErrors(['username' => 'User does not exist.']);
        }

        $profile = auth()->user()->activeFamilyProfile;

        $alreadyInvited = HouseholdInvitation::where('family_profile_id', $profile->id)
                                             ->where('invited_user_id', $invitedUser->id)
                                             ->where('status', 'pending')
                                             ->exists();
        if ($alreadyInvited) {
            return back()->withErrors(['username' => 'User already has a pending invitation.']);
        }

        $alreadyMember = FamilyMember::where('family_profile_id', $profile->id)
                                     ->where('user_id', $invitedUser->id)
                                     ->exists();
        if ($alreadyMember) {
            return back()->withErrors(['username' => 'User is already in this household.']);
        }

        HouseholdInvitation::create([
            'family_profile_id' => $profile->id,
            'invited_user_id'   => $invitedUser->id,
            'invited_by'        => auth()->id(),
        ]);

        return back()->with('success', 'Invitation sent to ' . $invitedUser->username . '!');
    }

    // Accept invitation
    public function acceptInvitation(HouseholdInvitation $invitation)
    {
        $invitation->update(['status' => 'accepted']);

        FamilyMember::create([
            'family_profile_id' => $invitation->family_profile_id,
            'user_id'           => auth()->id(),
            'role'              => 'Member',
            'is_owner'          => false,
        ]);

        $user = auth()->user();
        if (!$user->active_family_profile_id) {
            $user->update(['active_family_profile_id' => $invitation->family_profile_id]);
        }

        return redirect()->route('family.index')
                         ->with('success', 'You joined the household!');
    }

    // Decline invitation
    public function declineInvitation(HouseholdInvitation $invitation)
    {
        $invitation->update(['status' => 'declined']);
        return back()->with('success', 'Invitation declined.');
    }

    // Update household
    public function updateHousehold(Request $request)
    {
        $request->validate([
            'household_name' => ['required', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:255'],
            'disaster_risks' => ['nullable', 'array'],
        ]);

        $user    = auth()->user();
        $profile = $user->activeFamilyProfile;
        $member  = $user->activeMember();

        // Only owner can edit
        if (!$member->is_owner) {
            abort(403, 'Only the owner can edit this household.');
        }

        $profile->update([
            'household_name' => $request->household_name,
            'address'        => $request->address,
            'disaster_risks' => $request->disaster_risks,
        ]);

        return back()->with('success', 'Household updated successfully!');
    }

    // Delete household
    public function deleteHousehold()
    {
        $user    = auth()->user();
        $profile = $user->activeFamilyProfile;
        $member  = $user->activeMember();

        // Only owner can delete
        if (!$member->is_owner) {
            abort(403, 'Only the owner can delete this household.');
        }

        // Switch to another household if user has one
        $otherHousehold = $user->familyProfiles()
                            ->where('family_profiles.id', '!=', $profile->id)
                            ->first();

        // Delete the household
        $profile->delete();

        if ($otherHousehold) {
            $user->update(['active_family_profile_id' => $otherHousehold->id]);
            return redirect()->route('family.index')
                            ->with('success', 'Household deleted. Switched to ' . $otherHousehold->household_name);
        }

        $user->update(['active_family_profile_id' => null]);
        return redirect()->route('onboarding.index')
                        ->with('success', 'Household deleted.');
    }

    // Update member role
    public function updateRole(Request $request, FamilyMember $member)
    {
        $request->validate([
            'role' => ['required', 'string', 'max:255'],
        ]);

        $member->update(['role' => $request->role]);
        return back()->with('success', 'Role updated!');
    }

    // Remove a member
    public function removeMember(FamilyMember $member)
    {
        // Get the user being removed
        $removedUser = $member->user;

        // Delete the member record
        $member->delete();

        // If their active household was this one, clear it or switch to another
            if ($removedUser && $removedUser->active_family_profile_id === $member->family_profile_id) {
            // Check if they have another household
            $anotherHousehold = $removedUser->familyMembers()->first();

            if ($anotherHousehold) {
                // Switch them to another household they belong to
                $removedUser->update([
                    'active_family_profile_id' => $anotherHousehold->family_profile_id
                ]);
            } else {
                // No other household — send them back to onboarding
                $removedUser->update([
                    'active_family_profile_id' => null
                ]);
            }
        }

        return back()->with('success', 'Member removed.');
    }

    // Update location sharing
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude'         => ['required', 'numeric'],
            'longitude'        => ['required', 'numeric'],
            'location_sharing' => ['required', 'boolean'],
        ]);

        $member = auth()->user()->activeMember();
        $member->update([
            'latitude'         => $request->latitude,
            'longitude'        => $request->longitude,
            'location_sharing' => $request->location_sharing,
        ]);

        return back()->with('success', 'Location updated!');
    }

    public function assignRole(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:family_members,id',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\MemberRole::create([
            'family_member_id' => $request->member_id,
            'event_name' => $request->role,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Role assigned successfully!');
    }

    public function removeRoleTask($roleId)
    {
        $role = \App\Models\MemberRole::findOrFail($roleId);
        $role->delete();

        return back()->with('success', 'Role removed successfully.');
    }
}