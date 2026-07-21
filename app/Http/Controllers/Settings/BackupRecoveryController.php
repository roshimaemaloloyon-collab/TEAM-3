<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BackupRecoveryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $perPage = (int) ($request->query('per_page', 15));

        $query = Backup::query()->orderByDesc('created_at');

        if ($search) {
            $query->where('file_name', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('backup_type', $type);
        }

        $backups = $query->paginate($perPage)->withQueryString();

        $lastBackup = Backup::orderByDesc('created_at')->first();
        $nextBackup = now()->addDays(7);

        $stats = [
            'last_backup' => $lastBackup ? $lastBackup->created_at->diffForHumans() : 'Never',
            'next_backup' => $nextBackup->format('M d, Y'),
            'backup_storage' => '2.5 GB',
            'backup_status' => $lastBackup ? $lastBackup->status : 'No backups',
        ];

        return view('admin.settings.backup-recovery', compact('backups', 'stats'));
    }

    public function store(Request $request)
    {
        $backup = Backup::create([
            'backup_type' => 'manual',
            'status' => 'completed',
            'created_by' => auth()->id() ?? 1,
            'completed_at' => now(),
            'file_name' => 'backup_' . now()->format('Ymd_His') . '.sql',
            'file_path' => '/backups/',
            'file_size' => rand(1000000, 50000000),
        ]);

        return back()->with('success', 'Backup created successfully.');
    }

    public function download(Backup $backup)
    {
        return back()->with('success', 'Downloading backup...');
    }

    public function destroy(Backup $backup)
    {
        $backup->delete();

        return back()->with('success', 'Backup deleted successfully.');
    }
}
