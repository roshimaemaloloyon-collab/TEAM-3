<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\DriverBadge;
use Illuminate\Http\Request;

class RecognitionController extends Controller
{
    public function awards(Request $request)
    {
        $drivers = Driver::notArchived()->orderByDesc('performance_score')->paginate(10);
        $badges = DriverBadge::with('driver')->orderByDesc('awarded_at')->limit(12)->get();
        return view('admin.recognition', compact('drivers', 'badges'));
    }

    public function badges(Request $request)
    {
        $badges = DriverBadge::with('driver')->orderByDesc('created_at')->paginate(15);
        return view('admin.recognition', compact('badges'));
    }

    public function leaderboard(Request $request)
    {
        $topDrivers = Driver::notArchived()->orderByDesc('performance_score')->take(10)->get();
        return view('admin.recognition', compact('topDrivers'));
    }

    public function history(Request $request)
    {
        $badges = DriverBadge::with('driver')->orderByDesc('awarded_at')->paginate(15);
        return view('admin.recognition', compact('badges'));
    }

    public function certificates(Request $request)
    {
        $drivers = Driver::notArchived()->where('performance_score', '>=', 4.5)->get();
        return view('admin.recognition', compact('drivers'));
    }

    public function analytics(Request $request)
    {
        $totalBadges = DriverBadge::count();
        $topBadgeCount = DriverBadge::distinct('driver_id')->count();
        return view('admin.recognition', compact('totalBadges', 'topBadgeCount'));
    }
}
