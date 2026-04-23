<?php

namespace App\Http\Controllers;

use App\Models\ClientComment;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function home()
    {
        // Check if table exists before querying
        $comments = [];
        if (Schema::hasTable('client_comments')) {
            $comments = ClientComment::where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('home', compact('comments'));
    }

    public function disclaimer()
    {
        return view('disclaimer');
    }

    public function terms()
    {
        return view('terms');
    }
}
