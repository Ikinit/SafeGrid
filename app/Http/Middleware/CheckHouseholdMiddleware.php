<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHouseholdMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            $activeid = $user->active_family_profile_id;

            if ($activeid) {
                // Verify user is still actually a member of the active household
                $stillMember = $user->familyMembers()
                                    ->where('family_profile_id', $activeid)
                                    ->exists();

                if (!$stillMember) {
                    // Find another household or send to onboarding
                    $another = $user->familyMembers()->first();

                    if ($another) {
                        $user->update(['active_family_profile_id' => $another->family_profile_id]);
                    } else {
                        $user->update(['active_family_profile_id' => null]);
                    }

                    if (!$request->routeIs('onboarding.*')) {
                        return redirect()->route('onboarding.index')
                                        ->with('error', 'You have been removed from your household.');
                    }
                }
            } else {
                if (!$request->routeIs('onboarding.*')) {
                    return redirect()->route('onboarding.index');
                }
            }
        }

        return $next($request);
    }
}