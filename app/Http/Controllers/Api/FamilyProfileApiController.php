<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FamilyProfile;
use App\Models\FamilyMember;
use App\Models\HouseholdInvitation;
use Illuminate\Http\Request;

class FamilyProfileApiController extends Controller
{
    // Update location
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude'         => 'required|numeric',
            'longitude'        => 'required|numeric',
            'location_sharing' => 'required|boolean',
        ]);

        $member = auth()->user()->activeMember();
        $member->update($request->only('latitude', 'longitude', 'location_sharing'));

        return response()->json(['message' => 'Location updated successfully.']);
    }

    // Join Household via Code
    public function joinHousehold(Request $request)
    {
        $request->validate(['household_code' => 'required|string|size:6']);
        $profile = FamilyProfile::where('household_code', strtoupper($request->household_code))->first();

        if (!$profile) return response()->json(['message' => 'Code not found.'], 404);

        $invitation = HouseholdInvitation::create([
            'family_profile_id' => $profile->id,
            'invited_user_id'   => auth()->id(),
            'status'            => 'pending',
        ]);

        return response()->json(['message' => 'Join request sent!', 'request' => $invitation]);
    }

    // Get Household Details for Dashboard
    public function getDetails()
    {
        $user = auth()->user();
        $profile = FamilyProfile::find($user->active_family_profile_id);

        if (!$profile) {
            return response()->json(['error' => 'No active household'], 404);
        }

        try {
            $profile->load(['members.user', 'members.roles']); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Relationship Error: ' . $e->getMessage()], 500);
        }

        return response()->json(['household' => $profile]);
    }

    // Switch active household
    public function switchHousehold($profileId)
    {
        $user = auth()->user();
        $isMember = $user->familyMembers()->where('family_profile_id', $profileId)->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Unauthorized. You are not a member of this household.'], 403);
        }

        $user->active_family_profile_id = $profileId;
        $user->save();

        return response()->json(['message' => 'Switched household successfully.']);
    }

    // Invite a member by username
    public function inviteMember(Request $request)
    {
        $user = auth()->user();
        $profileId = $user->active_family_profile_id;
        
        if (!$profileId) return response()->json(['message' => 'No active household found.'], 404);

        $memberRecord = FamilyMember::where('family_profile_id', $profileId)->where('user_id', $user->id)->first();                                        
        if (!$memberRecord || !$memberRecord->is_owner) {
             return response()->json(['message' => 'Only the household owner can invite members.'], 403);
        }

        $targetUser = \App\Models\User::where('username', $request->username)->first();

        if (!$targetUser) return response()->json(['errors' => ['username' => ['User not found.']]], 422); 
        if ($targetUser->id === $user->id) return response()->json(['errors' => ['username' => ['You cannot invite yourself!']]], 422);

        $alreadyMember = FamilyMember::where('family_profile_id', $profileId)->where('user_id', $targetUser->id)->exists();
        if ($alreadyMember) return response()->json(['errors' => ['username' => ['This user is already a member.']]], 422);

        $alreadyInvited = HouseholdInvitation::where('family_profile_id', $profileId)
            ->where('invited_user_id', $targetUser->id)->where('status', 'pending')->exists();
        if ($alreadyInvited) return response()->json(['errors' => ['username' => ['An invitation is already pending.']]], 422);

        HouseholdInvitation::create([
            'family_profile_id' => $profileId,
            'invited_user_id'   => $targetUser->id,
            'invited_by'        => $user->id,
            'status'            => 'pending',
        ]);

        return response()->json(['message' => 'Invitation sent successfully to ' . $targetUser->username . '!']);
    }

    // ─────────────────────────────────────────────────────────────────
    // INVITATION MANAGEMENT METHODS
    // ─────────────────────────────────────────────────────────────────

    // Accept an invitation
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

        return response()->json(['message' => 'You successfully joined the household!']);
    }

    // Decline an invitation
    public function declineInvitation(HouseholdInvitation $invitation)
    {
        $invitation->update(['status' => 'declined']);
        return response()->json(['message' => 'Invitation declined.']);
    }

    // Approve a join-by-code request (owner only)
    public function approveJoinRequest(HouseholdInvitation $invitation)
    {
        $profile = auth()->user()->activeFamilyProfile;

        if ($profile->id !== $invitation->family_profile_id || !auth()->user()->activeMember()?->is_owner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invitation->update(['status' => 'accepted']);

        FamilyMember::create([
            'family_profile_id' => $invitation->family_profile_id,
            'user_id'           => $invitation->invited_user_id,
            'role'              => 'Member',
            'is_owner'          => false,
        ]);

        $invitedUser = $invitation->invitedUser;
        if ($invitedUser && !$invitedUser->active_family_profile_id) {
            $invitedUser->update(['active_family_profile_id' => $invitation->family_profile_id]);
        }

        return response()->json(['message' => 'Join request approved.']);
    }

    // Decline a join-by-code request (owner only)
    public function declineJoinRequest(HouseholdInvitation $invitation)
    {
        $profile = auth()->user()->activeFamilyProfile;

        if ($profile->id !== $invitation->family_profile_id || !auth()->user()->activeMember()?->is_owner) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invitation->update(['status' => 'declined']);
        return response()->json(['message' => 'Join request declined.']);
    }

    // Remove a member from the household
    public function removeMember(\App\Models\FamilyMember $member)
    {
        $user = auth()->user();
        $profile = $user->activeFamilyProfile;

        // Security 1: Make sure the person making the request is actually the owner
        if (!$user->activeMember()?->is_owner) {
            return response()->json(['message' => 'Unauthorized. Only the owner can remove members.'], 403);
        }

        // Security 2: Make sure the owner doesn't accidentally delete themselves
        if ($member->is_owner) {
            return response()->json(['message' => 'You cannot remove the household owner.'], 400);
        }

        $removedUser = $member->user;
        $username = $removedUser ? $removedUser->username : 'Member';

        // Delete the member record from the database
        $member->delete();

        // If the removed user's active household was this one, safely switch them to another one (or null)
        if ($removedUser && $removedUser->active_family_profile_id === $profile->id) {
            $anotherHousehold = $removedUser->familyMembers()->first();
            
            $removedUser->update([
                'active_family_profile_id' => $anotherHousehold ? $anotherHousehold->family_profile_id : null
            ]);
        }

        return response()->json(['message' => $username . ' has been successfully removed from the household.']);
    }

    // Cancel a pending join request (the requesting user)
    public function cancelJoinRequest(\App\Models\HouseholdInvitation $invitation)
    {
        // Security: Ensure only the person who made the request can cancel it
        if ($invitation->invited_user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invitation->delete();
        
        return response()->json(['message' => 'Join request cancelled.']);
    }

    // ─────────────────────────────────────────────────────────────────
    // ROLE MANAGEMENT
    // ─────────────────────────────────────────────────────────────────

    // Assign a role to a member
    public function assignRole(Request $request)
    {
        $request->validate([
            'member_id'   => 'required|exists:family_members,id',
            'role'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Security: Only the owner can assign roles
        if (!$user->activeMember()?->is_owner) {
            return response()->json(['message' => 'Unauthorized. Only the owner can assign roles.'], 403);
        }

        // Security: Verify the member actually belongs to the active household
        $member = \App\Models\FamilyMember::where('id', $request->member_id)
                    ->where('family_profile_id', $user->active_family_profile_id)
                    ->first();

        if (!$member) {
            return response()->json(['message' => 'Member not found in this household.'], 404);
        }

        // Create the role
        \App\Models\MemberRole::create([
            'family_member_id' => $request->member_id,
            'event_name'       => $request->role,
            'description'      => $request->description,
        ]);

        return response()->json(['message' => 'Role assigned successfully!']);
    }

    // Remove a specific role
    public function removeRoleTask($roleId)
    {
        // Security: Only the owner can remove roles
        if (!auth()->user()->activeMember()?->is_owner) {
            return response()->json(['message' => 'Unauthorized. Only the owner can remove roles.'], 403);
        }

        $role = \App\Models\MemberRole::find($roleId);
        
        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }

        // Delete the role
        $role->delete();

        return response()->json(['message' => 'Role removed successfully.']);
    }
    
    // ─────────────────────────────────────────────────────────────────
    // HOUSEHOLD MANAGEMENT (Edit & Delete)
    // ─────────────────────────────────────────────────────────────────

    // Update household details
    public function updateHousehold(Request $request)
    {
        $request->validate([
            'household_name' => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'disaster_risks' => 'nullable|array',
        ]);

        $user    = auth()->user();
        $profile = $user->activeFamilyProfile;
        $member  = $user->activeMember();

        if (!$profile || !$member) {
            return response()->json(['message' => 'No active household found.'], 404);
        }

        // Only owner can edit
        if (!$member->is_owner) {
            return response()->json(['message' => 'Unauthorized. Only the owner can edit this household.'], 403);
        }

        $profile->update([
            'household_name' => $request->household_name,
            'address'        => $request->address,
            'disaster_risks' => $request->disaster_risks,
        ]);

        return response()->json(['message' => 'Household updated successfully!']);
    }

    // Delete the entire household
    public function destroyHousehold()
    {
        $user    = auth()->user();
        $profile = $user->activeFamilyProfile;
        $member  = $user->activeMember();

        if (!$profile || !$member) {
            return response()->json(['message' => 'No active household found.'], 404);
        }

        // Only owner can delete
        if (!$member->is_owner) {
            return response()->json(['message' => 'Unauthorized. Only the owner can delete this household.'], 403);
        }

        // Check if the user belongs to any OTHER households so we don't leave them stranded
        $otherHousehold = $user->familyProfiles()
                            ->where('family_profiles.id', '!=', $profile->id)
                            ->first();

        // Delete the household (Cascade rules in your DB should handle the members and roles)
        $profile->delete();

        if ($otherHousehold) {
            // Switch them to their other household
            $user->update(['active_family_profile_id' => $otherHousehold->id]);
            return response()->json(['message' => 'Household deleted. Switched to ' . $otherHousehold->household_name]);
        }

        // No other households? Send them back to the onboarding state
        $user->update(['active_family_profile_id' => null]);
        return response()->json(['message' => 'Household deleted successfully.']);
    }
}