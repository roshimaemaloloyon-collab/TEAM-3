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

    public function update(Request $request, $id)
    {
        $training = Training::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'instructor' => 'required|string',
            'venue' => 'nullable|string',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string',
        ]);

        $training->update($request->only(['title', 'category', 'instructor', 'venue', 'capacity', 'status']));

        return back()->with('success', 'Training session updated successfully.');
    }

    public function cancel($id)
    {
        $training = Training::findOrFail($id);
        $training->status = 'cancelled';
        $training->save();

        return back()->with('success', 'Training schedule cancelled.');
    }

    public function destroy($id)
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return back()->with('success', 'Training session record archived/deleted successfully.');
    }
}
