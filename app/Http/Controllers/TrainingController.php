<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('instructor', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainings = $query->latest()->paginate(15);

        return view('admin.training.overview', compact('trainings'));
    }

    public function programs(Request $request)
    {
        $query = Training::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('instructor', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainings = $query->latest()->paginate(15);

        return view('admin.training.programs', compact('trainings'));
    }

    public function schedule(Request $request)
    {
        $query = Training::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('instructor', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainings = $query->latest()->paginate(15);

        return view('admin.training.schedule', compact('trainings'));
    }

    public function upcoming(Request $request)
    {
        $query = Training::where('status', 'upcoming');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('instructor', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $trainings = $query->latest()->paginate(15);

        return view('admin.training.upcoming', compact('trainings'));
    }

    public function completed(Request $request)
    {
        $query = Training::where('status', 'completed');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('instructor', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $trainings = $query->latest()->paginate(15);

        return view('admin.training.completed', compact('trainings'));
    }

    public function calendar(Request $request)
    {
        $trainings = Training::all();

        return view('admin.training.calendar', compact('trainings'));
    }

    public function registrations(Request $request)
    {
        $registrations = \App\Models\TrainingRegistration::with(['driver', 'training'])->paginate(15);

        return view('admin.training.registration', compact('registrations'));
    }

    public function attendance(Request $request)
    {
        $attendance = \App\Models\Attendance::with(['driver', 'training'])->paginate(15);

        return view('admin.training.attendance', compact('attendance'));
    }

    public function evaluations(Request $request)
    {
        $evaluations = \App\Models\TrainingEvaluation::with(['driver', 'training'])->paginate(15);

        return view('admin.training.evaluation', compact('evaluations'));
    }

    public function history(Request $request)
    {
        $history = Training::where('status', 'completed')->paginate(15);

        return view('admin.training.history', compact('history'));
    }

    public function certificates(Request $request)
    {
        $certificates = \App\Models\Certificate::with(['driver', 'training'])->paginate(15);

        return view('admin.training.certificates', compact('certificates'));
    }

    public function reports(Request $request)
    {
        return view('admin.training.reports');
    }
}
