<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\AlertRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertApiController extends Controller
{
    // GET /api/alerts
    public function index()
    {
        $user = Auth::user();
        $alerts = $user->is_admin ? Alert::latest()->get() : Alert::visibleTo($user)->latest()->get();

        // Mark as read
        foreach ($alerts as $alert) {
            AlertRead::firstOrCreate(['alert_id' => $alert->id, 'user_id' => $user->id]);
        }

        return response()->json($alerts);
    }

    // POST /api/alerts
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) return response()->json(['message' => 'Unauthorized'], 403);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $alert = Alert::create(array_merge($validated, ['type' => 'admin', 'user_id' => null]));

        return response()->json(['message' => 'Alert published!', 'alert' => $alert], 201);
    }

    // DELETE /api/alerts/{alert}
    public function destroy(Alert $alert)
    {
        if (!Auth::user()->is_admin) return response()->json(['message' => 'Unauthorized'], 403);
        $alert->delete();
        return response()->json(['message' => 'Alert deleted.']);
    }
}