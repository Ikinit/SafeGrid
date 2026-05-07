<?php

namespace App\Providers;

use App\Models\Alert;
use App\Models\AlertRead;
use App\Models\GoBagItems;
use App\Models\GoBagItemsFamily;
use App\Models\HouseholdInvitation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share household list with every view (existing)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('households', Auth::user()->familyProfiles);
            }
        });

        // Share alerts + invitation data with the navigation partial only
        View::composer('layouts.navigation', function ($view) {
            if (! Auth::check()) {
                return;
            }

            $user     = Auth::user();
            $deadline = Carbon::today()->addDays(30);

            // ── Auto expiry check — personal go-bag items ─────────
            GoBagItems::where('user_id', $user->id)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', $deadline)
                ->get()
                ->each(function (GoBagItems $item) use ($user) {
                    $exists = Alert::where('type', 'expiry')
                        ->where('checklist_item_id', $item->id)
                        ->where('user_id', $user->id)
                        ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
                        ->exists();

                    if ($exists) return;

                    $expired = $item->expiry_date->lt(now());
                    $days    = abs((int) now()->diffInDays($item->expiry_date, false));

                    Alert::create([
                        'type'              => 'expiry',
                        'user_id'           => $user->id,
                        'checklist_item_id' => $item->id,
                        'title'             => $expired
                            ? "⚠️ \"{$item->name}\" has expired"
                            : "⏰ \"{$item->name}\" expires in {$days} day(s)",
                        'message'           => $expired
                            ? "\"{$item->name}\" ({$item->category}) has expired. Please replace it to keep your go-bag ready."
                            : "\"{$item->name}\" ({$item->category}) will expire in {$days} day(s). Consider restocking soon.",
                    ]);
                });

            // ── Auto expiry check — family go-bag items ───────────
            if ($user->activeFamilyProfile) {
                GoBagItemsFamily::where('family_profile_id', $user->activeFamilyProfile->id)
                    ->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', $deadline)
                    ->get()
                    ->each(function (GoBagItemsFamily $item) use ($user) {
                        $exists = Alert::where('type', 'expiry')
                            ->where('checklist_item_family_id', $item->id)
                            ->where('user_id', $user->id)
                            ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
                            ->exists();

                        if ($exists) return;

                        $expired = $item->expiry_date->lt(now());
                        $days    = abs((int) now()->diffInDays($item->expiry_date, false));

                        Alert::create([
                            'type'                     => 'expiry',
                            'user_id'                  => $user->id,
                            'checklist_item_family_id' => $item->id,
                            'title'                    => $expired
                                ? "⚠️ Family item \"{$item->name}\" has expired"
                                : "⏰ Family item \"{$item->name}\" expires in {$days} day(s)",
                            'message'                  => $expired
                                ? "Family go-bag item \"{$item->name}\" ({$item->category}) has expired. Please replace it."
                                : "Family go-bag item \"{$item->name}\" ({$item->category}) will expire in {$days} day(s). Consider restocking soon.",
                        ]);
                    });
            }

            // ── Alerts ────────────────────────────────────────────
            $recentAlerts = Alert::visibleTo($user)
                ->latest()
                ->take(5)
                ->get()
                ->map(function (Alert $alert) use ($user) {
                    $alert->is_read = AlertRead::where('alert_id', $alert->id)
                                               ->where('user_id', $user->id)
                                               ->exists();
                    return $alert;
                });

            $unreadAlertCount = Alert::visibleTo($user)
                ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
                ->count();

            // ── Household Invitations ─────────────────────────────
            $pendingInvitations = HouseholdInvitation::where('invited_user_id', $user->id)
                ->where('status', 'pending')
                ->with(['familyProfile', 'invitedBy'])
                ->latest()
                ->get();

            $pendingInvitationCount = $pendingInvitations->count();

            $view->with(compact(
                'recentAlerts',
                'unreadAlertCount',
                'pendingInvitations',
                'pendingInvitationCount'
            ));
        });
    }
}