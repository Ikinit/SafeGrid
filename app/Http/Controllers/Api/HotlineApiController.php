<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use Illuminate\Http\Request;

/**
 * HotlineApiController
 *
 * GET /api/hotlines?location=Tacloban
 * Returns hotlines filtered by location (always includes National records).
 * Used by the AJAX location-filter dropdown on the contacts page.
 */
class HotlineApiController extends Controller
{
    public function index(Request $request)
    {
        $location = $request->query('location');

        $query = Hotline::where('is_active', true);

        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('location', 'National')
                  ->orWhere('location', 'like', '%' . $location . '%');
            });
        }

        $hotlines = $query->orderBy('location')->get();

        // Also return all distinct locations for the dropdown
        $locations = Hotline::where('is_active', true)
            ->distinct()
            ->pluck('location')
            ->sort()
            ->values();

        return response()->json([
            'hotlines'  => $hotlines,
            'locations' => $locations,
        ]);
    }
}