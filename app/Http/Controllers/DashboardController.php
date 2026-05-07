<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hotline;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $hotlines = Hotline::all();
        $posts = Post::latest()->take(3)->get(); 
        
        return view('dashboard', compact('hotlines', 'posts'));
    }

    public function guest()
    {
        $hotlines = Hotline::all();
        $posts = Post::latest()->take(3)->get(); 
        
        return view('welcome', compact('hotlines', 'posts'));
    }
}