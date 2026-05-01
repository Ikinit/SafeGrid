<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use App\Models\Hotline;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    private function getContacts()
    {
        $user = auth()->user();

        if ($user->active_family_profile_id) {
            return EmergencyContact::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->whereNull('family_profile_id');
            })->orWhere('family_profile_id', $user->active_family_profile_id)
              ->get();
        }

        return EmergencyContact::where('user_id', $user->id)
                               ->whereNull('family_profile_id')
                               ->get();
    }

    public function index()
    {
        $user     = auth()->user();
        $contacts = $this->getContacts();

        // Split into personal and household
        $personalContacts   = $contacts->whereNull('family_profile_id');
        $householdContacts  = $contacts->whereNotNull('family_profile_id');

        // Get hotlines based on household address
        $location  = null;
        $hotlines  = collect();

        if ($user->active_family_profile_id) {
            $address  = $user->activeFamilyProfile->address ?? '';
            $location = $this->extractLocation($address);
            $hotlines = Hotline::where('is_active', true)
                               ->where(function ($q) use ($location) {
                                   $q->where('location', 'National')
                                     ->orWhere('location', 'like', '%' . $location . '%');
                               })
                               ->orderBy('location')
                               ->get();
        }

        // Get all available locations for the dropdown
        $locations = Hotline::where('is_active', true)
                            ->distinct()
                            ->pluck('location')
                            ->sort()
                            ->values();

        return view('contacts.index', compact(
            'personalContacts',
            'householdContacts',
            'hotlines',
            'location',
            'locations'
        ));
    }

    // Add personal contact
    public function storePersonal(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        EmergencyContact::create([
            'user_id'           => auth()->id(),
            'family_profile_id' => null,
            'name'              => $request->name,
            'contact_number'    => $request->contact_number,
        ]);

        return back()->with('success', 'Personal contact added!');
    }

    // Add household contact
    public function storeHousehold(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $user = auth()->user();

        if (!$user->active_family_profile_id) {
            return back()->withErrors(['name' => 'You need to be in a household first.']);
        }

        EmergencyContact::create([
            'user_id'           => $user->id,
            'family_profile_id' => $user->active_family_profile_id,
            'name'              => $request->name,
            'contact_number'    => $request->contact_number,
        ]);

        return back()->with('success', 'Household contact added!');
    }

    // Edit contact
    public function update(Request $request, EmergencyContact $contact)
    {
        $this->authorizeContact($contact);

        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
        ]);

        $contact->update([
            'name'           => $request->name,
            'contact_number' => $request->contact_number,
        ]);

        return back()->with('success', 'Contact updated!');
    }

    // Delete contact
    public function destroy(EmergencyContact $contact)
    {
        $this->authorizeContact($contact);
        $contact->delete();

        return back()->with('success', 'Contact deleted!');
    }

    // Filter hotlines by location
    public function filterHotlines(Request $request)
    {
        $location = $request->location;
        $hotlines = Hotline::where('is_active', true)
                           ->where(function ($q) use ($location) {
                               $q->where('location', 'National')
                                 ->orWhere('location', $location);
                           })
                           ->orderBy('location')
                           ->get();

        return back()->with('filtered_hotlines', $hotlines)
                     ->with('selected_location', $location);
    }

    // Make sure user can only modify their own contacts
    private function authorizeContact(EmergencyContact $contact)
    {
        $user = auth()->user();

        if ($contact->family_profile_id) {
            if ($contact->family_profile_id !== $user->active_family_profile_id) {
                abort(403);
            }
        } else {
            if ($contact->user_id !== $user->id) {
                abort(403);
            }
        }
    }

    // Extract city/location from address string
    private function extractLocation(string $address): string
    {
        // Try to match known locations in the address
        $knownLocations = Hotline::distinct()->pluck('location')->toArray();

        foreach ($knownLocations as $loc) {
            if (stripos($address, $loc) !== false) {
                return $loc;
            }
        }

        // Default to first word of address
        return explode(',', $address)[0] ?? 'National';
    }
}