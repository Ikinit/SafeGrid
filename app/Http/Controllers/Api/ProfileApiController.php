<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileApiController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $user->fill($request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]));

        if ($user->isDirty('email')) { $user->email_verified_at = null; }
        $user->save();

        return response()->json(['message' => 'Profile updated.', 'user' => $user]);
    }
}