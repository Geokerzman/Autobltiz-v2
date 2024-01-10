<?php

// app/Http/Controllers/PagesController.php

namespace App\Http\Controllers;

class PagesController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('posts.index');
        }

        $data = [
            'title' => 'Auto Blitz',
            'description' => 'Unleashing the Fast Lane Thrill! Discover a symphony of speed and style at AutoBlitz, where every car is a masterpiece in motion. Your journey to automotive excellence starts here!'
        ];

        return view('pages.index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About Us',
            'description' => 'Where you could find your dream vehicle'
        ];

        return view('pages.about', $data);
    }
}
