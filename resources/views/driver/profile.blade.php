<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TripWise — My Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #F44336;
            --primary-light: #EF5350;
            --primary-dark: #D32F2F;
            --white: #FFFFFF;
            --beige: #faf9f6;
            --beige-dark: #f1efe9;
            --charcoal: #1c1c1e;
            --charcoal-light: #2c2c2e;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --shadow: rgba(0, 0, 0, 0.06);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--beige);
            color: var(--text-dark);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        .page-header {
            background: linear-gradient(135deg, var(--charcoal) 0%, var(--charcoal-light) 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: 1.25rem;
            margin: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: 0 8px 24px rgba(28, 28, 30, 0.25);
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
        }

        .profile-header-info h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .profile-header-info p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .profile-container {
            max-width: 1000px;
            margin: 0 2rem 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .profile-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px var(--shadow);
            border: 1px solid var(--border);
        }

        .profile-card h3 {
            font-size: 1.1rem;
            color: var(--primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-card h3 i { font-size: 1rem; opacity: 0.7; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .info-item label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .info-item p {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active { background: #d1fae5; color: #065f46; }
        .status-pending { background: #ffedd5; color: #c2410c; }
        .status-review { background: #dbeafe; color: #1e40af; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-box {
            background: var(--beige);
            padding: 1rem;
            border-radius: 0.75rem;
            text-align: center;
        }

        .stat-box p:first-child {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        .stat-box p:last-child {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin: 0.25rem 0 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 2rem 2rem 0;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .back-link:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .page-header { flex-direction: column; text-align: center; }
            .profile-photo { width: 90px; height: 90px; }
            .info-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <a href="{{ url('/dashboard') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="page-header">
        <img src="{{ $driver->photo ?: asset('drivers/photo/' . ($driver->id ?? 1)) }}" alt="{{ $driver->full_name ?? 'Driver' }}" class="profile-photo">
        <div class="profile-header-info">
            <h1>{{ $driver->full_name ?? 'Driver' }}</h1>
            <p>{{ $driver->driver_id ?? '' }} • {{ $driver->branch ?? 'N/A' }} • {{ $driver->route_assignment ?? 'N/A' }} • {{ $driver->vehicle_assignment ?? 'Unassigned' }}</p>
            <div style="margin-top: 0.75rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <span class="status-badge status-{{ strtolower($driver->status ?? 'active') }}">{{ ucfirst($driver->status ?? 'Active') }}</span>
                <span class="status-badge" style="background:#d1fae5;color:#065f46;">{{ number_format($driver->performance_score ?? 4.9, 1) }} Performance</span>
                <span class="status-badge" style="background:#dbeafe;color:#1e40af;">{{ number_format($driver->trips_count ?? 0) }} Trips</span>
            </div>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-card">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>First Name</label>
                    <p>{{ $driver->first_name ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Middle Name</label>
                    <p>{{ $driver->middle_name ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Last Name</label>
                    <p>{{ $driver->last_name ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Birth Date</label>
                    <p>{{ $driver->birth_date ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Gender</label>
                    <p>{{ $driver->gender ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Civil Status</label>
                    <p>{{ $driver->civil_status ?? 'N/A' }}</p>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <label>Address</label>
                    <p>{{ $driver->address ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Contact Number</label>
                    <p>{{ $driver->contact_number ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Email Address</label>
                    <p>{{ $driver->email ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Emergency Contact Person</label>
                    <p>{{ $driver->emergency_contact_person ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Emergency Contact Number</label>
                    <p>{{ $driver->emergency_contact_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h3><i class="fas fa-briefcase"></i> Employment Details</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>Driver ID</label>
                    <p>{{ $driver->driver_id ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Date Hired</label>
                    <p>{{ $driver->date_hired ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Branch</label>
                    <p>{{ $driver->branch ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Vehicle Assignment</label>
                    <p>{{ $driver->vehicle_assignment ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Vehicle Type</label>
                    <p>{{ $driver->vehicle_type ?? 'N/A' }}</p>
                </div>
                <div class="info-item">
                    <label>Route Assignment</label>
                    <p>{{ $driver->route_assignment ?? 'N/A' }}</p>
                </div>
                <div class="info-item" style="grid-column: span 2;">
                    <label>Employment Status</label>
                    <p><span class="status-badge status-{{ strtolower($driver->status ?? 'active') }}">{{ ucfirst($driver->status ?? 'Active') }}</span></p>
                </div>
            </div>
        </div>

        <div class="profile-card" style="grid-column: 1 / -1;">
            <h3><i class="fas fa-chart-bar"></i> Performance Snapshot</h3>
            <div class="stats-grid">
                <div class="stat-box">
                    <p>{{ number_format($driver->performance_score ?? 4.9, 1) }}</p>
                    <p>Performance Score</p>
                </div>
                <div class="stat-box">
                    <p>{{ number_format($driver->trips_count ?? 0) }}</p>
                    <p>Completed Trips</p>
                </div>
                <div class="stat-box">
                    <p>{{ $driver->complaints_count ?? 0 }}</p>
                    <p>Complaints</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>