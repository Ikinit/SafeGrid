<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

/**
 * ContactApiController
 *
 * Mirrors EmergencyContactController but returns JSON instead of redirects.
 * The web controller is left completely intact; these are additive API endpoints.
 */
class ContactApiController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    // GET /api/contacts
    // Returns personal + household contacts for the authenticated user.
    // ──────────────────────────────────────────────────────────────────
    public function index()
    {
        $user = auth()->user();

        if ($user->active_family_profile_id) {
            $contacts = EmergencyContact::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)->whereNull('family_profile_id');
            })->orWhere('family_profile_id', $user->active_family_profile_id)->get();
        } else {
            $contacts = EmergencyContact::where('user_id', $user->id)
                ->whereNull('family_profile_id')->get();
        }

        return response()->json([
            'personal'   => $contacts->whereNull('family_profile_id')->values(),
            'household'  => $contacts->whereNotNull('family_profile_id')->values(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // POST /api/contacts/personal
    // ──────────────────────────────────────────────────────────────────
    public function storePersonal(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $contact = EmergencyContact::create([
            'user_id'           => auth()->id(),
            'family_profile_id' => null,
            'name'              => $validated['name'],
            'contact_number'    => $validated['contact_number'],
        ]);

        return response()->json([
            'message' => 'Personal contact added!',
            'contact' => $contact,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────────
    // POST /api/contacts/household
    // ──────────────────────────────────────────────────────────────────
    public function storeHousehold(Request $request)
    {
        $user = auth()->user();

        if (!$user->active_family_profile_id) {
            return response()->json(['message' => 'You need to be in a household first.'], 403);
        }

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $contact = EmergencyContact::create([
            'user_id'           => $user->id,
            'family_profile_id' => $user->active_family_profile_id,
            'name'              => $validated['name'],
            'contact_number'    => $validated['contact_number'],
        ]);

        return response()->json([
            'message' => 'Household contact added!',
            'contact' => $contact,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────────
    // PUT /api/contacts/{contact}
    // ──────────────────────────────────────────────────────────────────
    public function update(Request $request, EmergencyContact $contact)
    {
        $this->authorizeContact($contact);

        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $contact->update($validated);

        return response()->json([
            'message' => 'Contact updated!',
            'contact' => $contact->fresh(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // DELETE /api/contacts/{contact}
    // ──────────────────────────────────────────────────────────────────
    public function destroy(EmergencyContact $contact)
    {
        $this->authorizeContact($contact);
        $contact->delete();

        return response()->json(['message' => 'Contact deleted!']);
    }

    // ──────────────────────────────────────────────────────────────────
    // Authorization helper 
    // ──────────────────────────────────────────────────────────────────
    private function authorizeContact(EmergencyContact $contact)
    {
        $user = auth()->user();

        if ($contact->family_profile_id) {
            $member = $user->familyMembers()
                ->where('family_profile_id', $contact->family_profile_id)
                ->first();

            if (!$member) {
                abort(403, 'You are not a member of this household.');
            }

            if (!$member->is_owner) {
                abort(403, 'Only the household owner can edit household contacts.');
            }
        } else {
            if ($contact->user_id !== $user->id) {
                abort(403, 'This is not your contact.');
            }
        }
    }
}