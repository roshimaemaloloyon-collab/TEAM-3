<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;

class PerformanceReviewsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = PerformanceReview::with(['driver', 'reviewer'])->orderByDesc('review_date');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('review_type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $reviews = $query->paginate($perPage)->withQueryString();

        $stats = [
            'completed' => PerformanceReview::where('status', 'completed')->count(),
            'pending' => PerformanceReview::where('status', 'pending')->count(),
            'monthly' => PerformanceReview::where('review_type', 'monthly')->count(),
            'quarterly' => PerformanceReview::where('review_type', 'quarterly')->count(),
        ];

        return view('admin.performance.performance-reviews', compact('reviews', 'stats'));
    }
}
