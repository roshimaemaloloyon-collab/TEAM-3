<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class TrainingAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Attendance::with(['driver', 'training'])->orderByDesc('created_at');

        if ($search) {
            $query->whereHas('driver', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $attendance = $query->paginate($perPage)->withQueryString();

        $stats = [
            'registered' => Attendance::count(),
            'present' => Attendance::where('status', 'present')->count(),
            'absent' => Attendance::where('status', 'absent')->count(),
            'attendance_rate' => Attendance::count() > 0 ? number_format((Attendance::where('status', 'present')->count() / Attendance::count()) * 100, 1) . '%' : '0%',
        ];

        if (config('database.default') === 'pgsql') {
            $attendanceTrend = Attendance::selectRaw("TO_CHAR(created_at, 'MM') as month_num, COUNT(*) as total")
                ->whereNotNull('created_at')
                ->groupByRaw("TO_CHAR(created_at, 'MM')")
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        } else {
            $attendanceTrend = Attendance::selectRaw('strftime("%m", created_at) as month_num, COUNT(*) as total')
                ->whereNotNull('created_at')
                ->groupBy('month_num')
                ->orderBy('month_num')
            ->limit(6)
            ->get();
        }

        $attendanceDist = Attendance::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.training.attendance', compact('attendance', 'stats', 'attendanceTrend', 'attendanceDist'));
    }
}
