<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingRegistration;
use Illuminate\Http\Request;

class TrainingScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Training::with('creator')->orderByDesc('start_datetime');

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $trainings = $query->paginate($perPage)->withQueryString();

        $stats = [
            'upcoming' => Training::where('status', 'upcoming')->count(),
            'ongoing' => Training::where('status', 'ongoing')->count(),
            'completed' => Training::where('status', 'completed')->count(),
            'total' => Training::count(),
        ];

        if (config('database.default') === 'pgsql') {
            $scheduleData = Training::selectRaw("TO_CHAR(start_datetime, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('start_datetime')
                ->groupByRaw("TO_CHAR(start_datetime, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $scheduleData = Training::selectRaw('strftime("%m", start_datetime) as month_num, COUNT(*) as total')
                ->whereNotNull('start_datetime')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        $statusData = Training::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.training.schedule', compact('trainings', 'stats', 'scheduleData', 'statusData'));
    }
}
