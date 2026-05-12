<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoBagItems;
use App\Models\GoBagItemsFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * GoBagApiController
 *
 * Mirrors GoBagController but returns JSON instead of redirects.
 * The original web controller is left completely intact.
 */
class GoBagApiController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    // GET /api/gobag
    // Returns personal items + family items for the current user.
    // ──────────────────────────────────────────────────────────────────
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->activeFamilyProfile;

        $personalItems = GoBagItems::where('user_id', $user->id)
            ->orderBy('category')->get();

        $familyItems = $profile
            ? GoBagItemsFamily::where('family_profile_id', $profile->id)
                ->orderBy('category')->get()
            : collect();

        return response()->json([
            'personal'    => $personalItems,
            'family'      => $familyItems,
            'has_profile' => (bool) $profile,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // POST /api/gobag/personal
    // ──────────────────────────────────────────────────────────────────
    public function storePersonal(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'nullable|string',
            'expiry_date'      => 'nullable|date',
            'nutritional_info' => 'nullable|string',
        ]);

        $item = GoBagItems::create([
            'user_id'          => Auth::id(),
            'name'             => $validated['name'],
            'category'         => $validated['category'] ?? 'General',
            'expiry_date'      => $validated['expiry_date'] ?? null,
            'nutritional_info' => $validated['nutritional_info'] ?? null,
            'is_packed'        => false,
        ]);

        return response()->json([
            'message' => 'Personal item added!',
            'item'    => $item,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────────
    // PATCH /api/gobag/personal/{item}  (toggle is_packed)
    // ──────────────────────────────────────────────────────────────────
    public function updatePersonal(Request $request, GoBagItems $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $item->update(['is_packed' => $request->boolean('is_packed')]);

        return response()->json([
            'message'  => 'Item updated!',
            'is_packed' => $item->is_packed,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // DELETE /api/gobag/personal/{item}
    // ──────────────────────────────────────────────────────────────────
    public function destroyPersonal(GoBagItems $item)
    {
        if ($item->user_id !== Auth::id()) abort(403);

        $item->delete();

        return response()->json(['message' => 'Item removed!']);
    }

    // ──────────────────────────────────────────────────────────────────
    // POST /api/gobag/family
    // ──────────────────────────────────────────────────────────────────
    public function storeFamily(Request $request)
    {
        $profile = Auth::user()->activeFamilyProfile;

        if (!$profile) {
            return response()->json(['message' => 'No active household found.'], 403);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'nullable|string',
            'expiry_date'      => 'nullable|date',
            'nutritional_info' => 'nullable|string',
        ]);

        $item = GoBagItemsFamily::create([
            'family_profile_id' => $profile->id,
            'name'              => $validated['name'],
            'category'          => $validated['category'] ?? 'General',
            'expiry_date'       => $validated['expiry_date'] ?? null,
            'nutritional_info'  => $validated['nutritional_info'] ?? null,
            'is_packed'         => false,
        ]);

        return response()->json([
            'message' => 'Family item added!',
            'item'    => $item,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────────
    // PATCH /api/gobag/family/{item}  (toggle is_packed)
    // ──────────────────────────────────────────────────────────────────
    public function updateFamily(Request $request, GoBagItemsFamily $item)
    {
        $profile = Auth::user()->activeFamilyProfile;

        if (!$profile || $item->family_profile_id !== $profile->id) abort(403);

        $item->update(['is_packed' => $request->boolean('is_packed')]);

        return response()->json([
            'message'   => 'Family item updated!',
            'is_packed' => $item->is_packed,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────
    // DELETE /api/gobag/family/{item}
    // ──────────────────────────────────────────────────────────────────
    public function destroyFamily(GoBagItemsFamily $item)
    {
        $profile = Auth::user()->activeFamilyProfile;

        if (!$profile || $item->family_profile_id !== $profile->id) abort(403);

        $item->delete();

        return response()->json(['message' => 'Family item removed!']);
    }
}