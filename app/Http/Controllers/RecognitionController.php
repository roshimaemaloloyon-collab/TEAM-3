<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecognitionController extends Controller
{
    public function awards(Request $request)
    {
        return view('admin.recognition.awards');
    }

    public function badges(Request $request)
    {
        return view('admin.recognition.badges');
    }

    public function leaderboard(Request $request)
    {
        return view('admin.recognition.leaderboard');
    }

    public function history(Request $request)
    {
        return view('admin.recognition.history');
    }

    public function certificates(Request $request)
    {
        return view('admin.recognition.certificates');
    }

    public function analytics(Request $request)
    {
        return view('admin.recognition.analytics');
    }
}
