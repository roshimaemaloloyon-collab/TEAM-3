<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TripWise — Driver')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @stack('styles')

    <style>
        :root {
            --primary: #F44336;
            --primary-light: #EF5350;
            --primary-dark: #D32F2F;
            --white: #FFFFFF;
            --beige: #faf9f6;
            --beige-dark: #f1efe9;
            --charcoal: #1c1c1e;
            --charcoal-dark: #111112;
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
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--charcoal);
            border-right: 1px solid var(--charcoal-light);
            z-index: 1000;
            transition: transform 0.3s ease, width 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.2);
        }

        .sidebar.collapsed {
            width: 72px;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .user-info {
            display: none;
        }

        .logo-area {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--charcoal-light);
            min-height: 70px;
        }

        .logo-area img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .logo-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--white);
            white-space: nowrap;
        }

        .nav-menu {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            margin: 0.15rem 0.75rem;
            border-radius: 0.75rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .nav-item:hover {
            background: var(--charcoal-light);
            color: var(--white);
        }

        .nav-item.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.35);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .nav-section-header {
            padding: 1.25rem 1.25rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #64748b;
            user-select: none;
        }

        .nav-section-header:not(:first-child) {
            margin-top: 0.5rem;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 1rem;
        }

        .user-area {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--charcoal-light);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .user-info { overflow: hidden; }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background: var(--beige);
        }

        .main-content.expanded { margin-left: 72px; }

        .top-navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .toggle-btn:hover {
            background: var(--beige);
            color: var(--primary);
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box input {
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: 2rem;
            font-size: 0.9rem;
            width: 280px;
            background: var(--beige);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
            width: 320px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .datetime {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .icon-btn {
            position: relative;
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.15rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.2s;
            text-decoration: none;
        }

        .icon-btn:hover {
            background: var(--beige);
            color: var(--primary);
        }

        .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
            border: 2px solid var(--white);
        }

        .profile-img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid var(--border);
            transition: border-color 0.2s;
        }

        .profile-img:hover { border-color: var(--primary); }

        .dashboard-content { padding: 2rem; max-width: 1400px; }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .breadcrumb a { color: var(--primary); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            color: var(--primary);
            margin: 0 0 0.25rem;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px var(--shadow);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .card-icon.blue { background: #dbeafe; color: #1e40af; }
        .card-icon.green { background: #d1fae5; color: #065f46; }
        .card-icon.orange { background: #ffedd5; color: #c2410c; }
        .card-icon.purple { background: #f3e8ff; color: #7c3aed; }

        .card-info h3 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--text-dark);
        }

        .card-info p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0.25rem 0 0;
        }

        .table-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px var(--shadow);
            margin-bottom: 1.5rem;
        }

        .table-card h3 {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin: 0 0 1rem;
        }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th, td {
            padding: 0.85rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        th {
            background: var(--beige);
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:hover td { background: #f8fafc; }

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

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .chart-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px var(--shadow);
        }

        .chart-card h3 {
            font-size: 1rem;
            color: var(--text-dark);
            margin: 0 0 1rem;
        }

        .chart-wrapper {
            position: relative;
            height: 280px;
        }

        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

    @include('driver.partials.sidebar', ['driver' => $driver ?? null])

    <div class="main-content" id="mainContent">
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
            </div>

            <div class="navbar-right">
                <div class="datetime" id="datetime"></div>
                <button class="icon-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge"></span>
                </button>
                <img src="{{ $driver->photo ?: asset('drivers/photo/' . ($driver->id ?? 1)) }}" alt="Profile" class="profile-img">
            </div>
        </header>

        <main class="dashboard-content">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
