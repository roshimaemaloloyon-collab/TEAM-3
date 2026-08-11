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
                  ->orWhere('driver_id', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.documents', compact('drivers'));
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
     * Export driver records as CSV file.
     */
    public function export()
    {
        $drivers = Driver::notArchived()->get();
        $filename = "drivers_export_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Driver ID', 'Full Name', 'Contact Number', 'Email', 'Branch', 'Route', 'Vehicle', 'Vehicle Type', 'Status', 'Performance Score', 'Trips Count'];

        $callback = function() use($drivers, $columns) {
            $file = fopen('php://output', 'w');
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
