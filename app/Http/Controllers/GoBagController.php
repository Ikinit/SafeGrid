<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GoBagItems;        
use App\Models\GoBagItemsFamily;     
use Illuminate\Support\Facades\Auth;

class GoBagController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $personalItems = GoBagItems::where('user_id', $user->id)
            ->orderBy('category')
            ->get();

        $profile = $user->activeFamilyProfile;
        
        $familyItems = $profile 
            ? GoBagItemsFamily::where('family_profile_id', $profile->id)->orderBy('category')->get() 
            : collect();

        return view('gobag.index', compact('personalItems', 'familyItems', 'profile'));
    }

    public function storePersonal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'nutritional_info' => 'nullable|string',
        ]);

        GoBagItems::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'category' => $request->category ?? 'General',
            'expiry_date' => $request->expiry_date,
            'nutritional_info' => $request->nutritional_info,
            'is_packed' => false,
        ]);

        return back()->with('success', 'Personal item added!');
    }

    public function updatePersonal(Request $request, GoBagItems $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $item->update(['is_packed' => $request->has('is_packed') && $request->is_packed == '1' ? true : false]);

        return back()->with('success', 'Item updated!');
    }

    public function storeFamily(Request $request)
    {
        $profile = Auth::user()->activeFamilyProfile;

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'nutritional_info' => 'nullable|string',
        ]);

        GoBagItemsFamily::create([
            'family_profile_id' => $profile->id,
            'name' => $request->name,
            'category' => $request->category ?? 'General',
            'expiry_date' => $request->expiry_date,
            'nutritional_info' => $request->nutritional_info,
            'is_packed' => false,
        ]);

        return back()->with('success', 'Family item added!');
    }
    
    public function updateFamily(Request $request, GoBagItemsFamily $item)
    {
        $profile = Auth::user()->activeFamilyProfile;

        if ($item->family_profile_id !== $profile->id) abort(403);

        $item->update(['is_packed' => $request->is_packed]);

        return back()->with('success', 'Family item updated!');
    }

    public function destroyPersonal(GoBagItems $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $item->delete();

        return back()->with('success', 'Item removed!');
    }

    public function destroyFamily(GoBagItemsFamily $item)
    {
        $profile = Auth::user()->activeFamilyProfile;

        if ($item->family_profile_id !== $profile->id) abort(403);

        $item->delete();

        return back()->with('success', 'Family item removed!');
    }
}
