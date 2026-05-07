<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\AlertRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * GET /alerts
     * Lists all alerts visible to the current user.
     * Admins see all alerts; regular users see only their visible ones.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin) {
            $alerts = Alert::latest()->get();
        } else {
            $alerts = Alert::visibleTo($user)->latest()->get();
        }

        // Mark all as read when viewing the full list
        foreach ($alerts as $alert) {
            AlertRead::firstOrCreate([
                'alert_id' => $alert->id,
                'user_id'  => $user->id,
            ]);
        }

        return view('alerts.index', compact('alerts'));
    }

    /**
     * GET /alerts/create  (admin only)
     */
    public function create()
    {
        abort_unless(Auth::user()->is_admin, 403);
        return view('alerts.create');
    }

    /**
     * POST /alerts  (admin only)
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'type'    => 'required|in:admin,expiry',
        ]);

        // Admin-posted broadcast alerts always have type=admin and no user_id
        $validated['type']    = 'admin';
        $validated['user_id'] = null;

        Alert::create($validated);

        return redirect()->route('alerts.index')
                         ->with('success', 'Alert published successfully.');
    }

    /**
     * GET /alerts/{alert}/edit  (admin only)
     */
    public function edit(Alert $alert)
    {
        abort_unless(Auth::user()->is_admin, 403);
        return view('alerts.edit', compact('alert'));
    }

    /**
     * PUT /alerts/{alert}  (admin only)
     */
    public function update(Request $request, Alert $alert)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $alert->update($validated);

        return redirect()->route('alerts.index')
                         ->with('success', 'Alert updated.');
    }

    /**
     * DELETE /alerts/{alert}  (admin only)
     */
    public function destroy(Alert $alert)
    {
        abort_unless(Auth::user()->is_admin, 403);
        $alert->delete();

        return redirect()->route('alerts.index')
                         ->with('success', 'Alert deleted.');
    }
}
