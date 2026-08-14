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

    public function update(Request $request, $id)
    {
        $record = Attendance::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $record->status = $request->status;
        $record->remarks = $request->remarks;
        $record->save();

        return back()->with('success', 'Attendance record updated successfully.');
    }

    public function export(Request $request)
    {
        $id = $request->query('id');
        $record = Attendance::with(['driver', 'training'])->find($id);

        $driverName = $record->driver->name ?? 'Driver';
        $trainingTitle = $record->training->title ?? 'Training';
        $status = ucfirst($record->status ?? 'N/A');
        $checkIn = $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') : 'N/A';
        $checkOut = $record->check_out_time ? \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') : 'N/A';
        $remarks = $record->remarks ?? 'N/A';

        $filename = "attendance_slip_" . ($record ? $record->id : 'log') . ".pdf";

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<title>Training Attendance Verification Slip</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 30px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #ef4444; padding-bottom: 12px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #991b1b; margin: 0; font-size: 20px; text-transform: uppercase; }';
        $html .= '.box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px; }';
        $html .= '.field { margin-bottom: 8px; font-size: 13px; }';
        $html .= '.label { font-weight: bold; color: #64748b; text-transform: uppercase; font-size: 11px; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — ATTENDANCE SLIP</h1><p>Driver Training Attendance Verification Record</p></div>';
        $html .= '<div class="box">';
        $html .= '<div class="field"><span class="label">Driver Name:</span> <strong>' . htmlspecialchars($driverName) . '</strong></div>';
        $html .= '<div class="field"><span class="label">Training Program:</span> ' . htmlspecialchars($trainingTitle) . '</div>';
        $html .= '<div class="field"><span class="label">Attendance Status:</span> <strong>' . htmlspecialchars($status) . '</strong></div>';
        $html .= '<div class="field"><span class="label">Check-In / Out:</span> ' . htmlspecialchars($checkIn) . ' - ' . htmlspecialchars($checkOut) . '</div>';
        $html .= '<div class="field"><span class="label">Remarks:</span> ' . htmlspecialchars($remarks) . '</div>';
        $html .= '</div>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
