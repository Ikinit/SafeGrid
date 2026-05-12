<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotline;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * GET /api/dashboard/stats
     * Returns the latest hotlines and recent posts for the dashboard feed.
     */
    public function index()
    {
        // Fetch data
        $hotlines = Hotline::all();
        $posts = Post::latest()->take(3)->get();

        // Return structured JSON
        return response()->json([
            'hotlines' => $hotlines,
            'posts'    => $posts,
            'status'   => 'success'
        ]);
    }
}