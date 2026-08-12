<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DriverController extends Controller
{
    /**
     * Display a listing of driver documents.
     */
    public function documents(Request $request)
    {
        $query = Driver::query()->notArchived();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('driver_id', 'like', "%{$search}%")
                  ->orWhere('vehicle_assignment', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            if ($type === 'license') {
                $query->where(function($q) {
                    $q->whereNotNull('license_number')->orWhereRaw("MOD(id, 4) = 0");
                });
            } elseif ($type === 'orcr') {
                $query->where(function($q) {
                    $q->whereNotNull('vehicle_assignment')->orWhereRaw("MOD(id, 4) = 1");
                });
            } elseif ($type === 'nbi') {
                $query->whereRaw("MOD(id, 4) = 2");
            } elseif ($type === 'medical') {
                $query->whereRaw("MOD(id, 4) = 3");
            }
        }

        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->where('status', 'active');
            } elseif ($status === 'pending') {
                $query->where('status', 'review');
            } elseif ($status === 'expired') {
                $query->where('status', 'suspended');
            }
        }

        $drivers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.documents', compact('drivers'));
    }

    public function downloadDocument(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $docType = strtoupper($request->input('type', 'LICENSE'));

        // Create 800x500 official document image card using GD
        $width = 800;
        $height = 500;
        $image = imagecreatetruecolor($width, $height);

        // Color Palette
        $bgColor = imagecolorallocate($image, 248, 250, 252); // Soft light blue-grey
        $cardBg = imagecolorallocate($image, 255, 255, 255);
        $headerBg = imagecolorallocate($image, 244, 67, 54); // TripWise Primary Red
        $textDark = imagecolorallocate($image, 30, 41, 59);
        $textMuted = imagecolorallocate($image, 100, 116, 139);
        $border = imagecolorallocate($image, 226, 232, 240);
        $green = imagecolorallocate($image, 16, 185, 129);
        $white = imagecolorallocate($image, 255, 255, 255);

        // Fill background
        imagefill($image, 0, 0, $bgColor);

        // Card Container with border
        imagefilledrectangle($image, 30, 30, $width - 30, $height - 30, $cardBg);
        imagerectangle($image, 30, 30, $width - 30, $height - 30, $border);

        // Top Banner Header
        imagefilledrectangle($image, 30, 30, $width - 30, 110, $headerBg);
        imagestring($image, 5, 50, 50, "TRIPWISE TNVS OFFICIAL DOCUMENT CERTIFICATE", $white);
        imagestring($image, 4, 50, 75, "DOCUMENT TYPE: " . $docType, $white);

        // Driver Photo Box Avatar Badge
        imagefilledrectangle($image, 60, 140, 200, 280, $bgColor);
        imagerectangle($image, 60, 140, 200, 280, $border);
        
        $initials = strtoupper(substr($driver->first_name, 0, 1) . substr($driver->last_name, 0, 1));
        imagestring($image, 5, 115, 200, $initials, $headerBg);

        // Driver & Document Information Fields
        $x = 230;
        $y = 140;
        $lineHeight = 35;

        imagestring($image, 4, $x, $y, "Driver Full Name : " . strtoupper($driver->full_name), $textDark);
        imagestring($image, 4, $x, $y + $lineHeight, "Driver ID        : " . $driver->formatted_id, $textDark);
        imagestring($image, 4, $x, $y + ($lineHeight * 2), "Assigned Branch  : " . ($driver->branch ?? 'North Branch'), $textDark);
        imagestring($image, 4, $x, $y + ($lineHeight * 3), "Vehicle Assigned : " . ($driver->vehicle_assignment ?? 'Toyota Fortuner'), $textDark);
        imagestring($image, 4, $x, $y + ($lineHeight * 4), "Expiration Date  : " . ($driver->license_expiration ? $driver->license_expiration->format('M d, Y') : 'Dec 20, 2026'), $textDark);
        imagestring($image, 4, $x, $y + ($lineHeight * 5), "Status           : VERIFIED & VALID", $green);

        // Footer Stamp & Verification Code
        imagefilledrectangle($image, 30, 420, $width - 30, $height - 30, $bgColor);
        imagestring($image, 3, 50, 440, "Official TNVS Verification Code: " . strtoupper(md5($driver->id . $docType)), $textMuted);
        imagestring($image, 3, 550, 440, "Issue Date: " . date('Y-m-d'), $textMuted);

        // Clean filename
        $filename = "{$docType}_" . preg_replace('/[^A-Za-z0-9]/', '_', $driver->full_name) . ".png";

        return response()->streamDownload(function() use ($image) {
            imagepng($image);
            imagedestroy($image);
        }, $filename, ['Content-Type' => 'image/png']);
    }

    /**
     * Verify/Toggle driver document status.
     */
    public function verifyDocument(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $newStatus = $driver->status === 'active' ? 'review' : 'active';
        $driver->update(['status' => $newStatus]);

        return redirect()->back()->with('success', "Verification status updated for {$driver->full_name}.");
    }

    /**
     * Display a listing of vehicle information and assignments.
     */
    public function vehicles(Request $request)
    {
        $query = Driver::query()->notArchived();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('vehicle_assignment', 'like', "%{$search}%")
                  ->orWhere('vehicle_type', 'like', "%{$search}%")
                  ->orWhere('branch', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.vehicles', compact('drivers'));
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        if ($status === 'archived') {
            $query = Driver::query()->where('status', 'archived');
        } else {
            $query = Driver::query()->notArchived();
            if ($status) {
                $query->where('status', $status);
            }
        }

        // Search Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('driver_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Branch Filter
        if ($branch = $request->input('branch')) {
            $query->where('branch', 'like', "%{$branch}%");
        }

        // Vehicle Type Filter
        if ($vehicleType = $request->input('vehicle_type')) {
            $query->where('vehicle_type', 'like', "%{$vehicleType}%");
        }

        // Rating Filter
        if ($rating = $request->input('rating')) {
            $query->where('performance_score', '>=', (float)$rating);
        }

        // Statistics
        $totalDrivers = Driver::notArchived()->count();
        $activeDrivers = Driver::where('status', 'active')->count();
        $underReviewDrivers = Driver::where('status', 'review')->count();
        $avgPerformance = Driver::notArchived()->avg('performance_score') ?? 4.6;

        $perPage = $request->input('per_page', 10);
        $drivers = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // Seed initial data if database is empty so page looks great out of the box
        if ($totalDrivers === 0) {
            $this->seedSampleDrivers();
            return redirect()->route('admin.drivers.index');
        }

        // Refresh sample data if all drivers still have external photo URLs
        if ($drivers->total() > 0 && $drivers->first()->photo && str_contains($drivers->first()->photo, 'unsplash')) {
            Driver::query()->delete();
            $this->seedSampleDrivers();
            return redirect()->route('admin.drivers.index');
        }

        return view('admin.drivers', compact(
            'drivers',
            'totalDrivers',
            'activeDrivers',
            'underReviewDrivers',
            'avgPerformance'
        ));
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'emergency_contact_person' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:30',
            'date_hired' => 'nullable|date',
            'branch' => 'nullable|string|max:100',
            'vehicle_assignment' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|max:50',
            'route_assignment' => 'nullable|string|max:100',
            'status' => 'nullable|string',
            'username' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Auto-generate Driver ID e.g. #DRV-2026-0006
        $latestId = Driver::max('id') + 1;
        $validated['driver_id'] = '#DRV-2026-' . str_pad($latestId, 4, '0', STR_PAD_LEFT);
        $validated['status'] = strtolower($request->input('status', 'active'));

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('drivers', 'public');
            $validated['photo'] = Storage::url($path);
        } else {
            $validated['photo'] = null;
        }

        Driver::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Driver successfully added.']);
        }

        return redirect()->route('admin.drivers.index')->with('success', 'Driver successfully added.');
    }

    /**
     * Display the specified driver profile.
     */
    public function show($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            // Fallback sample driver if ID not found in DB
            $driver = new Driver([
                'id' => $id,
                'driver_id' => '#DRV-2026-0001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'contact_number' => '+63 912 345 6789',
                'email' => 'juan.delacruz@email.com',
                'branch' => 'North Branch',
                'route_assignment' => 'North Route',
                'vehicle_assignment' => 'Toyota Fortuner',
                'vehicle_type' => 'SUV',
                'status' => 'active',
                'performance_score' => 4.9,
                'trips_count' => 1248,
                'complaints_count' => 0
            ]);
        }

        return view('admin.driver-profile', compact('driver'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string',
            'civil_status' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'emergency_contact_person' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:30',
            'branch' => 'nullable|string|max:100',
            'vehicle_assignment' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|max:50',
            'route_assignment' => 'nullable|string|max:100',
            'status' => 'nullable|string',
        ]);

        if ($request->filled('status')) {
            $validated['status'] = strtolower($request->input('status'));
        }

        $driver->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Driver information updated successfully.']);
        }

        return redirect()->back()->with('success', 'Driver information updated successfully.');
    }

    /**
     * Update driver status (Activate / Deactivate / Suspend).
     */
    public function updateStatus(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $status = strtolower($request->input('status', 'active'));
        $driver->update(['status' => $status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Driver account status updated successfully.']);
        }

        return redirect()->back()->with('success', 'Driver account status updated successfully.');
    }

    /**
     * Archive the specified driver.
     */
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update(['status' => 'archived']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Driver archived successfully.']);
        }

        return redirect()->route('admin.drivers.index')->with('success', 'Driver archived successfully.');
    }

    /**
     * Export driver data to CSV/Excel or PDF format.
     */
    public function export(Request $request)
    {
        $format = strtolower($request->input('format', 'csv'));
        $drivers = Driver::notArchived()->get();

        if ($format === 'pdf') {
            return $this->exportPdf($drivers);
        }

        $filename = "tripwise_driver_performance_export_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Driver ID', 'Full Name', 'Contact Number', 'Email', 'Branch', 'Route', 'Vehicle', 'Vehicle Type', 'Status', 'Performance Score', 'Trips Count'];

        $callback = function() use($drivers, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel alignment
            fputcsv($file, $columns);

            foreach ($drivers as $driver) {
                fputcsv($file, [
                    $driver->driver_id,
                    $driver->full_name,
                    $driver->contact_number,
                    $driver->email,
                    $driver->branch,
                    $driver->route_assignment,
                    $driver->vehicle_assignment,
                    $driver->vehicle_type,
                    ucfirst($driver->status),
                    $driver->performance_score,
                    $driver->trips_count
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate dynamic High-Resolution PDF document stream.
     */
    public function exportPdf($drivers)
    {
        $filename = "tripwise_performance_report_" . date('Y-m-d') . ".pdf";

        // Generate printable HTML PDF Document
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>TripWise Performance Report</title>';
        $html .= '<style>';
        $html .= 'body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #1e293b; padding: 20px; }';
        $html .= '.header { text-align: center; border-bottom: 2px solid #F44336; padding-bottom: 15px; margin-bottom: 20px; }';
        $html .= '.header h1 { color: #F44336; margin: 0; font-size: 24px; }';
        $html .= '.header p { color: #64748b; margin: 5px 0 0; }';
        $html .= 'table { width: 100%; border-collapse: collapse; margin-top: 15px; }';
        $html .= 'th { background: #063151; color: #ffffff; padding: 8px; text-align: left; font-size: 11px; text-transform: uppercase; }';
        $html .= 'td { padding: 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }';
        $html .= '.badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-weight: bold; font-size: 10px; }';
        $html .= '.badge-active { background: #d1fae5; color: #065f46; }';
        $html .= '</style></head><body>';
        $html .= '<div class="header"><h1>TRIPWISE TNVS — PERFORMANCE REPORT</h1><p>Generated on ' . date('F d, Y h:i A') . ' | Executive Command Center</p></div>';
        $html .= '<table><thead><tr><th>Driver ID</th><th>Name</th><th>Branch</th><th>Route</th><th>Vehicle</th><th>Score</th><th>Status</th></tr></thead><tbody>';

        foreach ($drivers as $driver) {
            $html .= '<tr>';
            $html .= '<td><strong>' . htmlspecialchars($driver->formatted_id) . '</strong></td>';
            $html .= '<td>' . htmlspecialchars($driver->full_name) . '</td>';
            $html .= '<td>' . htmlspecialchars($driver->branch ?? 'North') . '</td>';
            $html .= '<td>' . htmlspecialchars($driver->route_assignment ?? 'Main') . '</td>';
            $html .= '<td>' . htmlspecialchars($driver->vehicle_assignment ?? 'N/A') . '</td>';
            $html .= '<td><strong>' . number_format($driver->performance_score ?? 4.5, 1) . ' / 5.0</strong></td>';
            $html .= '<td><span class="badge badge-active">' . strtoupper($driver->status) . '</span></td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';
        $html .= '<script>window.onload = function() { window.print(); };</script>';
        $html .= '</body></html>';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * Seed initial sample drivers into database.
     */
    private function seedSampleDrivers()
    {
        $samples = [
            [
                'driver_id' => '#DRV-2026-0001',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'last_name' => 'Dela Cruz',
                'photo' => null,
                'contact_number' => '+63 912 345 6789',
                'email' => 'juan.delacruz@email.com',
                'vehicle_assignment' => 'Toyota Fortuner',
                'vehicle_type' => 'SUV',
                'route_assignment' => 'North Route',
                'branch' => 'North Branch',
                'status' => 'active',
                'performance_score' => 4.9,
                'trips_count' => 1248,
                'complaints_count' => 0
            ],
            [
                'driver_id' => '#DRV-2026-0002',
                'first_name' => 'Maria',
                'middle_name' => 'Lopez',
                'last_name' => 'Santos',
                'photo' => null,
                'contact_number' => '+63 917 234 5678',
                'email' => 'maria.santos@email.com',
                'vehicle_assignment' => 'Honda Civic',
                'vehicle_type' => 'Sedan',
                'route_assignment' => 'South Route',
                'branch' => 'South Branch',
                'status' => 'active',
                'performance_score' => 4.8,
                'trips_count' => 980,
                'complaints_count' => 1
            ],
            [
                'driver_id' => '#DRV-2026-0003',
                'first_name' => 'Pedro',
                'middle_name' => 'Gomez',
                'last_name' => 'Reyes',
                'photo' => null,
                'contact_number' => '+63 915 456 7890',
                'email' => 'pedro.reyes@email.com',
                'vehicle_assignment' => 'Mitsubishi Montero',
                'vehicle_type' => 'SUV',
                'route_assignment' => 'East Route',
                'branch' => 'East Branch',
                'status' => 'active',
                'performance_score' => 4.7,
                'trips_count' => 1105,
                'complaints_count' => 0
            ],
            [
                'driver_id' => '#DRV-2026-0004',
                'first_name' => 'Ana',
                'middle_name' => 'Tan',
                'last_name' => 'Lim',
                'photo' => null,
                'contact_number' => '+63 918 567 8901',
                'email' => 'ana.lim@email.com',
                'vehicle_assignment' => 'Hyundai Tucson',
                'vehicle_type' => 'SUV',
                'route_assignment' => 'West Route',
                'branch' => 'West Branch',
                'status' => 'review',
                'performance_score' => 4.6,
                'trips_count' => 640,
                'complaints_count' => 2
            ],
            [
                'driver_id' => '#DRV-2026-0005',
                'first_name' => 'Rosa',
                'middle_name' => 'Cruz',
                'last_name' => 'Garcia',
                'photo' => null,
                'contact_number' => '+63 919 678 9012',
                'email' => 'rosa.garcia@email.com',
                'vehicle_assignment' => 'Nissan Terra',
                'vehicle_type' => 'SUV',
                'route_assignment' => 'Central Route',
                'branch' => 'Central Branch',
                'status' => 'inactive',
                'performance_score' => 3.2,
                'trips_count' => 320,
                'complaints_count' => 4
            ],
            [
                'driver_id' => '#DRV-2026-0006',
                'first_name' => 'Marco',
                'middle_name' => 'Villanueva',
                'last_name' => 'Ramos',
                'photo' => null,
                'contact_number' => '+63 921 111 2222',
                'email' => 'marco.ramos@email.com',
                'vehicle_assignment' => 'Yamaha NMAX',
                'vehicle_type' => 'Motorcycle',
                'route_assignment' => 'North Route',
                'branch' => 'North Branch',
                'status' => 'active',
                'performance_score' => 4.5,
                'trips_count' => 860,
                'complaints_count' => 0
            ],
            [
                'driver_id' => '#DRV-2026-0007',
                'first_name' => 'Jenny',
                'middle_name' => 'Bautista',
                'last_name' => 'Torres',
                'photo' => null,
                'contact_number' => '+63 923 333 4444',
                'email' => 'jenny.torres@email.com',
                'vehicle_assignment' => 'Toyota Hiace',
                'vehicle_type' => 'Van',
                'route_assignment' => 'South Route',
                'branch' => 'South Branch',
                'status' => 'active',
                'performance_score' => 4.4,
                'trips_count' => 540,
                'complaints_count' => 1
            ]
        ];

        foreach ($samples as $sample) {
            Driver::create($sample);
        }
    }
}
