<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TripWise — Driver Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #F44336;
            --primary-light: #EF5350;
            --primary-dark: #D32F2F;
            --accent: #F44336;
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--beige);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar */
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

        .sidebar.collapsed .nav-item:hover {
            background: var(--charcoal-light);
            color: var(--white);
        }

        .logo-area {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border);
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
            padding: 0.75rem 1.5rem;
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

        .user-area {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
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
            border: 2px solid var(--primary);
        }

        .user-info {
            overflow: hidden;
        }

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

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background: var(--beige);
        }

        .main-content.expanded {
            margin-left: 72px;
        }

        /* Top Navbar */
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
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
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

        .profile-img:hover {
            border-color: var(--primary);
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
            max-width: 1400px;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--charcoal) 0%, var(--charcoal-light) 100%);
            color: var(--white);
            padding: 2rem;
            border-radius: 1.25rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 24px rgba(28, 28, 30, 0.25);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            right: -50px;
            top: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .welcome-text h2 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .welcome-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 1;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px var(--shadow);
            border: 1px solid var(--border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px var(--shadow);
        }

        .card-icon {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .card-icon.blue { background: #dbeafe; color: #1e40af; }
        .card-icon.green { background: #d1fae5; color: #065f46; }
        .card-icon.purple { background: #ede9fe; color: #6b21a8; }
        .card-icon.orange { background: #ffedd5; color: #c2410c; }
        .card-icon.teal { background: #ccfbf1; color: #115e59; }
        .card-icon.gold { background: #fef3c7; color: #92400e; }

        .card-info h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--charcoal);
            line-height: 1.2;
        }

        .card-info p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px var(--shadow);
            border: 1px solid var(--border);
        }

        .chart-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--charcoal);
        }

        .chart-wrapper {
            position: relative;
            height: 280px;
        }

        /* Sections */
        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .section-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px var(--shadow);
            border: 1px solid var(--border);
        }

        .section-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--charcoal);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-card h3 i {
            font-size: 1rem;
            opacity: 0.7;
        }

        .list-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item:hover {
            background: var(--beige);
            margin: 0 -1rem;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 0.5rem;
        }

        .item-icon {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .item-content {
            flex: 1;
            min-width: 0;
        }

        .item-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .item-subtitle {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
        }

        .item-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #ffedd5; color: #c2410c; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-purple { background: #ede9fe; color: #6b21a8; }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: var(--text-dark);
        }

        .action-btn:hover {
            background: var(--charcoal);
            color: var(--white);
            border-color: var(--charcoal);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(28, 28, 30, 0.2);
        }

        .action-btn i {
            font-size: 1.25rem;
            color: var(--primary);
            transition: color 0.2s;
        }

        .action-btn:hover i {
            color: var(--white);
        }

        .action-btn span {
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 8px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
            }
            .charts-grid, .section-grid {
                grid-template-columns: 1fr;
            }
            .search-box input {
                width: 180px;
            }
            .search-box input:focus {
                width: 220px;
            }
        }

        @media (max-width: 640px) {
            .dashboard-content {
                padding: 1rem;
            }
            .welcome-card {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            .top-navbar {
                padding: 0.75rem 1rem;
            }
            .search-box {
                display: none;
            }
            .datetime {
                display: none;
            }
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .mobile-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

<div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <img src="{{ asset('tripwise_logo.png') }}" alt="Tripwise">
        <span class="logo-text">Tripwise</span>
    </div>

    <nav class="nav-menu">
        <a href="#" class="nav-item active"><i class="fas fa-th-large"></i><span class="nav-text">Dashboard</span></a>
        <a href="#" class="nav-item"><i class="fas fa-chart-line"></i><span class="nav-text">My Performance</span></a>
        <a href="#" class="nav-item"><i class="fas fa-brain"></i><span class="nav-text">My Competencies</span></a>
        <a href="#" class="nav-item"><i class="fas fa-book-open"></i><span class="nav-text">Learning Modules</span></a>
        <a href="#" class="nav-item"><i class="fas fa-chalkboard-teacher"></i><span class="nav-text">My Trainings</span></a>
        <a href="#" class="nav-item"><i class="fas fa-rocket"></i><span class="nav-text">Career Growth</span></a>
        <a href="#" class="nav-item"><i class="fas fa-trophy"></i><span class="nav-text">Recognition & Achievements</span></a>
        <a href="#" class="nav-item"><i class="fas fa-users"></i><span class="nav-text">Peer-to-Peer Evaluation</span></a>
        <a href="#" class="nav-item"><i class="fas fa-file-alt"></i><span class="nav-text">Reports</span></a>
        <a href="#" class="nav-item"><i class="fas fa-bell"></i><span class="nav-text">Notifications</span></a>
        <a href="#" class="nav-item"><i class="fas fa-user"></i><span class="nav-text">My Profile</span></a>
        <a href="#" class="nav-item"><i class="fas fa-cog"></i><span class="nav-text">Settings</span></a>
        <a href="#" class="nav-item" style="color: #ef4444;"><i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span></a>
    </nav>

    <div class="user-area">
        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80" alt="Driver" class="user-avatar">
        <div class="user-info">
            <div class="user-name">Juan Dela Cruz</div>
            <div class="user-role">Professional Driver</div>
        </div>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Navbar -->
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
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80" alt="Profile" class="profile-img">
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="dashboard-content">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-text">
                <h2>Welcome back, Juan! 👋</h2>
                <p>Here's your driving performance summary for today.</p>
            </div>
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&h=200&q=80" alt="Juan Dela Cruz" class="welcome-avatar">
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <div class="summary-card">
                <div class="card-icon blue"><i class="fas fa-star"></i></div>
                <div class="card-info">
                    <h3>4.8</h3>
                    <p>Overall Performance Score</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon green"><i class="fas fa-smile"></i></div>
                <div class="card-info">
                    <h3>4.9</h3>
                    <p>Customer Rating</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon purple"><i class="fas fa-handshake"></i></div>
                <div class="card-info">
                    <h3>4.6</h3>
                    <p>Peer Evaluation Score</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon orange"><i class="fas fa-certificate"></i></div>
                <div class="card-info">
                    <h3>12</h3>
                    <p>Trainings Completed</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon teal"><i class="fas fa-book-reader"></i></div>
                <div class="card-info">
                    <h3>85%</h3>
                    <p>Learning Progress</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon gold"><i class="fas fa-medal"></i></div>
                <div class="card-info">
                    <h3>8</h3>
                    <p>Achievement Badges</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <h3><i class="fas fa-chart-area"></i> Monthly Performance Trend</h3>
                <div class="chart-wrapper">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-chart-bar"></i> Competency Progress</h3>
                <div class="chart-wrapper">
                    <canvas id="competencyChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-chart-pie"></i> Training Completion Progress</h3>
                <div class="chart-wrapper">
                    <canvas id="trainingChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sections Grid -->
        <div class="section-grid">
            <!-- Recent Performance -->
            <div class="section-card">
                <h3><i class="fas fa-clipboard-check"></i> Recent Performance Summary</h3>
                <div class="list-item">
                    <div class="item-icon blue"><i class="fas fa-route"></i></div>
                    <div class="item-content">
                        <div class="item-title">Safe Driving Assessment</div>
                        <div class="item-subtitle">July 2026 • Score: 96%</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 96%"></div></div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="item-icon green"><i class="fas fa-gas-pump"></i></div>
                    <div class="item-content">
                        <div class="item-title">Fuel Efficiency Check</div>
                        <div class="item-subtitle">June 2026 • Score: 92%</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 92%"></div></div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="item-icon purple"><i class="fas fa-user-check"></i></div>
                    <div class="item-content">
                        <div class="item-title">Customer Service Review</div>
                        <div class="item-subtitle">June 2026 • Score: 98%</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: 98%"></div></div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Training -->
            <div class="section-card">
                <h3><i class="fas fa-calendar-alt"></i> Upcoming Training Schedule</h3>
                <div class="list-item">
                    <div class="item-icon orange"><i class="fas fa-shield-alt"></i></div>
                    <div class="item-content">
                        <div class="item-title">Defensive Driving Workshop</div>
                        <div class="item-subtitle">July 15, 2026 • 9:00 AM</div>
                    </div>
                    <span class="item-badge badge-warning">Upcoming</span>
                </div>
                <div class="list-item">
                    <div class="item-icon blue"><i class="fas fa-first-aid"></i></div>
                    <div class="item-content">
                        <div class="item-title">First Aid Certification</div>
                        <div class="item-subtitle">July 22, 2026 • 1:00 PM</div>
                    </div>
                    <span class="item-badge badge-info">Scheduled</span>
                </div>
                <div class="list-item">
                    <div class="item-icon green"><i class="fas fa-leaf"></i></div>
                    <div class="item-content">
                        <div class="item-title">Eco-Driving Techniques</div>
                        <div class="item-subtitle">August 5, 2026 • 10:00 AM</div>
                    </div>
                    <span class="item-badge badge-success">Enrolled</span>
                </div>
            </div>

            <!-- Peer Evaluation -->
            <div class="section-card">
                <h3><i class="fas fa-comments"></i> Recent Peer Evaluation Feedback</h3>
                <div class="list-item">
                    <div class="item-icon purple"><i class="fas fa-quote-left"></i></div>
                    <div class="item-content">
                        <div class="item-title">"Excellent teamwork on the north route"</div>
                        <div class="item-subtitle">From: Maria Santos • July 10, 2026</div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="item-icon blue"><i class="fas fa-quote-left"></i></div>
                    <div class="item-content">
                        <div class="item-title">"Always punctual and reliable"</div>
                        <div class="item-subtitle">From: Pedro Reyes • July 8, 2026</div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="item-icon green"><i class="fas fa-quote-left"></i></div>
                    <div class="item-content">
                        <div class="item-title">"Great help during peak hours"</div>
                        <div class="item-subtitle">From: Ana Lim • July 5, 2026</div>
                    </div>
                </div>
            </div>

            <!-- Recognition -->
            <div class="section-card">
                <h3><i class="fas fa-award"></i> Latest Recognition & Achievements</h3>
                <div class="list-item">
                    <div class="item-icon gold"><i class="fas fa-medal"></i></div>
                    <div class="item-content">
                        <div class="item-title">Safe Driver of the Month</div>
                        <div class="item-subtitle">June 2026 • 100% safety record</div>
                    </div>
                    <span class="item-badge badge-purple">Badge</span>
                </div>
                <div class="list-item">
                    <div class="item-icon orange"><i class="fas fa-star"></i></div>
                    <div class="item-content">
                        <div class="item-title">Customer Satisfaction Award</div>
                        <div class="item-subtitle">Q2 2026 • 4.9/5 rating</div>
                    </div>
                    <span class="item-badge badge-warning">Award</span>
                </div>
                <div class="list-item">
                    <div class="item-icon blue"><i class="fas fa-clock"></i></div>
                    <div class="item-content">
                        <div class="item-title">Perfect Attendance Streak</div>
                        <div class="item-subtitle">6 consecutive months</div>
                    </div>
                    <span class="item-badge badge-info">Streak</span>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="section-card" style="margin-bottom: 2rem;">
            <h3><i class="fas fa-bullhorn"></i> Notifications and Announcements</h3>
            <div class="list-item">
                <div class="item-icon blue"><i class="fas fa-info-circle"></i></div>
                <div class="item-content">
                    <div class="item-title">New safety protocol effective July 20, 2026</div>
                    <div class="item-subtitle">HR Department • 2 hours ago</div>
                </div>
            </div>
            <div class="list-item">
                <div class="item-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="item-content">
                    <div class="item-title">Your "Defensive Driving" training has been approved</div>
                    <div class="item-subtitle">Training Dept • 1 day ago</div>
                </div>
            </div>
            <div class="list-item">
                <div class="item-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="item-content">
                    <div class="item-title">Vehicle maintenance scheduled for July 18, 2026</div>
                    <div class="item-subtitle">Fleet Management • 3 days ago</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="#" class="action-btn">
                <i class="fas fa-chart-line"></i>
                <span>View My Performance</span>
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-book-open"></i>
                <span>Continue Learning</span>
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-calendar-check"></i>
                <span>View Training Schedule</span>
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-user-check"></i>
                <span>Evaluate a Peer</span>
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-download"></i>
                <span>Download My Certificate</span>
            </a>
        </div>
    </main>
</div>

<script>
    // Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('mainContent');
        const overlay = document.getElementById('mobileOverlay');

        if (window.innerWidth <= 1024) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        } else {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');
        }
    }

    // Close mobile sidebar on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('mobileOverlay').classList.remove('active');
        }
    });

    // Date/Time
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('datetime').textContent = now.toLocaleDateString('en-US', options);
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Performance Trend Chart
        const perfCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(perfCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Performance Score',
                    data: [4.2, 4.4, 4.3, 4.5, 4.6, 4.7, 4.8],
                    borderColor: '#F44336',
                    backgroundColor: 'rgba(244, 67, 54, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#F44336'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 4.0,
                        max: 5.0,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Competency Progress Chart
        const compCtx = document.getElementById('competencyChart').getContext('2d');
        new Chart(compCtx, {
            type: 'bar',
            data: {
                labels: ['Safety', 'Customer Service', 'Navigation', 'Vehicle Maintenance', 'Time Management'],
                datasets: [{
                    label: 'Competency Level',
                    data: [95, 92, 88, 85, 90],
                    backgroundColor: [
                        '#F44336',
                        '#EF5350',
                        '#1c1c1e',
                        '#D32F2F',
                        '#2c2c2e'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Training Completion Chart
        const trainCtx = document.getElementById('trainingChart').getContext('2d');
        new Chart(trainCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: [12, 3, 2],
                    backgroundColor: ['#F44336', '#f1f5f9', '#cbd5e1'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
