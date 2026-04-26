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

        if ($user && !$user->active_family_profile_id) {
            if (!$request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.index');
            }
        }

        return $next($request);
    }
}