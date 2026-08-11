<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecognitionController extends Controller
{
    public function awards(Request $request)
    {
        return view('admin.recognition');
    }

    public function badges(Request $request)
    {
        return view('admin.recognition');
    }

    public function leaderboard(Request $request)
    {
        return view('admin.recognition');
    }

    public function history(Request $request)
    {
        return view('admin.recognition');
    }

    public function certificates(Request $request)
    {
        return view('admin.recognition');
    }

    public function analytics(Request $request)
    {
        return view('admin.recognition');
    }
}
