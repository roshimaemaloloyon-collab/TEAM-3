<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'TripWise — Admin Panel'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>    <style>
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
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        /* Global Icon & SVG Size Bounds */
        svg, i.fas, i.far, i.fab, i.fa {
            max-width: 1.5rem !important;
            max-height: 1.5rem !important;
            box-sizing: border-box !important;
        }

        i.fa-chevron-right, i.fa-chevron-left, i.fa-chevron-down, i.fa-chevron-up, .chevron {
            font-size: 0.75rem !important;
            width: 12px !important;
            height: 12px !important;
            max-width: 12px !important;
            max-height: 12px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--charcoal);
            z-index: 1000;
            transition: transform 0.3s ease, width 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
        }

        .sidebar.collapsed { width: 72px; }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .user-info { display: none; }

        .sidebar.collapsed .nav-item { justify-content: center; padding: 0.75rem; }
        .sidebar.collapsed .nav-item i { margin-right: 0; font-size: 1.1rem; }
        .sidebar.collapsed .logo-area { justify-content: center; padding: 1.25rem 0.5rem; }
        .sidebar.collapsed .user-area { justify-content: center; padding: 1rem 0.5rem; }

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

        /* Navigation */
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

        .nav-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            user-select: none;
        }

        .nav-parent:hover {
            background: var(--charcoal-light);
            color: var(--white);
        }

        .nav-parent.open {
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
            border-radius: 0.75rem;
        }

        .nav-parent i.fa-chevron-right {
            font-size: 0.75rem !important;
            width: 12px !important;
            height: 12px !important;
            max-width: 12px !important;
            max-height: 12px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: transform 0.25s ease;
            opacity: 0.7;
        }

        .nav-parent.open i.fa-chevron-right {
            transform: rotate(90deg);
        }

        .nav-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-submenu.open {
            max-height: 700px;
        }

        .nav-subitem {
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            margin: 0.2rem 0.75rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 500;
            transition: all 0.2s ease;
            white-space: nowrap;
            position: relative;
        }

        .nav-subitem::before {
            content: '';
            position: absolute;
            left: 1.4rem;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #64748b;
            opacity: 0.5;
            transition: all 0.2s ease;
        }

        .nav-subitem:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
        }

        .nav-subitem:hover::before {
            background: var(--primary-light);
            opacity: 1;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.15);
        }

        .nav-subitem.active {
            color: var(--primary-light);
            background: rgba(244, 67, 54, 0.08);
            font-weight: 600;
        }

        .nav-subitem.active::before {
            background: var(--primary-light);
            opacity: 1;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.2);
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

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            background: var(--beige);
        }

        .main-content.expanded { margin-left: 72px; }

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

        .profile-dropdown {
            position: relative;
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

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 48px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            min-width: 180px;
            z-index: 200;
            overflow: hidden;
        }

        .dropdown-menu.show { display: block; }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .dropdown-menu a:hover { background: var(--beige); }

        .dropdown-menu a i { width: 16px; color: var(--text-muted); }

        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 0.25rem 0;
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
            max-width: 1400px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.75rem;
            color: var(--primary);
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6, 49, 81, 0.2);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--beige);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(6, 49, 81, 0.1);
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
            color: var(--primary);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }

        .chart-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-card h3 i { font-size: 1rem; opacity: 0.7; }

        .chart-wrapper {
            position: relative;
            height: 280px;
        }

        /* Tables */
        .table-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .table-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-card h3 i { font-size: 1rem; opacity: 0.7; }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        table thead {
            background: var(--beige);
        }

        table th {
            padding: 0.85rem 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--primary);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        table tbody tr {
            transition: background 0.2s;
        }

        table tbody tr:hover {
            background: var(--beige);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active { background: #d1fae5; color: #065f46; }
        .status-pending { background: #ffedd5; color: #c2410c; }
        .status-review { background: #dbeafe; color: #1e40af; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        /* Sections Grid */
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }

        .section-card h3 {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-card h3 i { font-size: 1rem; opacity: 0.7; }

        .list-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }

        .list-item:last-child { border-bottom: none; }

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

        .item-content { flex: 1; min-width: 0; }

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
        .badge-danger { background: #FCE9ED; color: #83122A; }
        .badge-critical { background: #fef2f2; color: #991b1b; }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.2);
        }

        .action-btn i {
            font-size: 1.25rem;
            color: var(--primary);
            transition: color 0.2s;
        }

        .action-btn:hover i { color: var(--white); }

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
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .charts-grid, .section-grid { grid-template-columns: 1fr; }
            .search-box input { width: 180px; }
            .search-box input:focus { width: 220px; }
        }

        @media (max-width: 640px) {
            .dashboard-content { padding: 1rem; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .top-navbar { padding: 0.75rem 1rem; }
            .search-box { display: none; }
            .datetime { display: none; }
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .mobile-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        @media (max-width: 1024px) {
            .mobile-overlay.active { display: block; }
        }

        /* Placeholder page styles */
        .placeholder-page {
            background: var(--white);
            border-radius: 1rem;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }

        .placeholder-page i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .placeholder-page h2 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .placeholder-page p {
            color: var(--text-muted);
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-bar select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.85rem;
            background: var(--white);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
        }

        .filter-bar select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(6, 49, 81, 0.1);
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Leaderboard */
        .leaderboard-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
        }

        .leaderboard-item:last-child {
            border-bottom: none;
        }

        .leaderboard-item:hover {
            background: var(--beige);
            border-radius: 0.75rem;
        }

        .rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #fff; }
        .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A0A0A0); color: #fff; }
        .rank-3 { background: linear-gradient(135deg, #CD7F32, #B87333); color: #fff; }
        .rank-other { background: var(--beige); color: var(--text-muted); }

        .driver-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .improvement-card {
            background: var(--white);
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(6, 49, 81, 0.06);
            border: 1px solid var(--border);
            margin-bottom: 1rem;
            border-left: 4px solid var(--danger);
        }

        .improvement-card h4 {
            color: var(--danger);
            margin-bottom: 0.5rem;
        }

        /* Notification panel */
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.85rem 0;
            border-bottom: 1px solid var(--border);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            flex-shrink: 0;
            margin-top: 6px;
        }

        .notification-dot.read {
            background: var(--border);
        }

        .overdue-row {
            background: #fef2f2;
        }

        .overdue-row td {
            color: #991b1b;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal-content {
            background: var(--white);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border);
            width: 100%;
            animation: fadeIn 0.25s ease forwards;
        }

        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--white);
            border-left: 4px solid var(--primary);
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            z-index: 3000;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            font-weight: 500;
            animation: slideInRight 0.3s ease forwards;
            max-width: 400px;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .toast-notification.success { border-left-color: var(--success); }
        .toast-notification.error { border-left-color: var(--danger); }
    </style>
</head>
<body>

<div class="mobile-overlay" id="mobileOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <img src="<?php echo e(asset('tripwise_logo.png')); ?>" alt="Tripwise">
        <span class="logo-text">Tripwise</span>
    </div>

    <nav class="nav-menu">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-th-large"></i><span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-section-header">Driver Management</div>

        <a href="<?php echo e(route('admin.drivers.index')); ?>" class="nav-parent <?php echo e(request()->routeIs('admin.drivers.*') ? 'open' : ''); ?>" data-target="nav-drivers" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-id-card"></i>
            <span class="nav-text">Manage Drivers</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.drivers.*') ? 'open' : ''); ?>" id="nav-drivers">
            <a href="<?php echo e(route('admin.drivers.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.drivers.index') ? 'active' : ''); ?>">Driver List</a>
            <a href="<?php echo e(route('admin.drivers.profile', 1)); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.drivers.profile') ? 'active' : ''); ?>">Driver Profile</a>
            <a href="<?php echo e(route('admin.drivers.documents')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.drivers.documents') ? 'active' : ''); ?>">Driver Documents</a>
            <a href="<?php echo e(route('admin.drivers.vehicles')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.drivers.vehicles') ? 'active' : ''); ?>">Vehicle Information</a>
        </div>

        <div class="nav-section-header">Talent Development</div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.performance.*') ? 'open' : ''); ?>" data-target="nav-performance" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-chart-line"></i>
            <span class="nav-text">Performance Management</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.performance.*') ? 'open' : ''); ?>" id="nav-performance">
            <a href="<?php echo e(route('admin.performance.drivers')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.drivers') ? 'active' : ''); ?>">Driver Performance</a>
            <a href="<?php echo e(route('admin.performance.kpi')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.kpi') ? 'active' : ''); ?>">KPI Monitoring</a>
            <a href="<?php echo e(route('admin.performance.reviews')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.reviews') ? 'active' : ''); ?>">Performance Reviews</a>
            <a href="<?php echo e(route('admin.performance.reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.reports') ? 'active' : ''); ?>">Performance Reports</a>
            <a href="<?php echo e(route('admin.performance.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.analytics') ? 'active' : ''); ?>">Performance Analytics</a>
            <a href="<?php echo e(route('admin.performance.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.performance.history') ? 'active' : ''); ?>">Performance History</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.competency.*') ? 'open' : ''); ?>" data-target="nav-competency" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-brain"></i>
            <span class="nav-text">Competency Management</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.competency.*') ? 'open' : ''); ?>" id="nav-competency">
            <a href="<?php echo e(route('admin.competency.assessments')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.assessments') ? 'active' : ''); ?>">Skills Assessment</a>
            <a href="<?php echo e(route('admin.competency.results')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.results') ? 'active' : ''); ?>">Assessment Results</a>
            <a href="<?php echo e(route('admin.competency.gap-analysis')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.gap-analysis') ? 'active' : ''); ?>">Gap Analysis</a>
            <a href="<?php echo e(route('admin.competency.plans')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.plans') ? 'active' : ''); ?>">Development Plan</a>
            <a href="<?php echo e(route('admin.competency.reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.reports') ? 'active' : ''); ?>">Competency Reports</a>
            <a href="<?php echo e(route('admin.competency.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.analytics') ? 'active' : ''); ?>">Competency Analytics</a>
            <a href="<?php echo e(route('admin.competency.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.competency.history') ? 'active' : ''); ?>">Competency History</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.learning.*') ? 'open' : ''); ?>" data-target="nav-learning" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-book-open"></i>
            <span class="nav-text">Learning Management</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.learning.*') ? 'open' : ''); ?>" id="nav-learning">
            <a href="<?php echo e(route('admin.learning.modules')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.modules') ? 'active' : ''); ?>">Learning Modules</a>
            <a href="<?php echo e(route('admin.learning.assessments')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.assessments') ? 'active' : ''); ?>">Assessments</a>
            <a href="<?php echo e(route('admin.learning.certificates')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.certificates') ? 'active' : ''); ?>">Certificates</a>
            <a href="<?php echo e(route('admin.learning.reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.reports') ? 'active' : ''); ?>">Learning Reports</a>
            <a href="<?php echo e(route('admin.learning.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.analytics') ? 'active' : ''); ?>">Learning Analytics</a>
            <a href="<?php echo e(route('admin.learning.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.learning.history') ? 'active' : ''); ?>">Learning History</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.training.*') ? 'open' : ''); ?>" data-target="nav-training" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-chalkboard-teacher"></i>
            <span class="nav-text">Training Management</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.training.*') ? 'open' : ''); ?>" id="nav-training">
            <a href="<?php echo e(route('admin.training.schedule')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.schedule') ? 'active' : ''); ?>">Training Schedule</a>
            <a href="<?php echo e(route('admin.training.attendance')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.attendance') ? 'active' : ''); ?>">Training Attendance</a>
            <a href="<?php echo e(route('admin.training.evaluations')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.evaluations') ? 'active' : ''); ?>">Training Evaluation</a>
            <a href="<?php echo e(route('admin.training.reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.reports') ? 'active' : ''); ?>">Training Reports</a>
            <a href="<?php echo e(route('admin.training.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.analytics') ? 'active' : ''); ?>">Training Analytics</a>
            <a href="<?php echo e(route('admin.training.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.training.history') ? 'active' : ''); ?>">Training History</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.succession.*') ? 'open' : ''); ?>" data-target="nav-succession" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-rocket"></i>
            <span class="nav-text">Succession Planning</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.succession.*') ? 'open' : ''); ?>" id="nav-succession">
            <a href="<?php echo e(route('admin.succession.leadership')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.leadership') ? 'active' : ''); ?>">Leadership Potential</a>
            <a href="<?php echo e(route('admin.succession.career-path')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.career-path') ? 'active' : ''); ?>">Career Path</a>
            <a href="<?php echo e(route('admin.succession.development-plan')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.development-plan') ? 'active' : ''); ?>">Development Plan</a>
            <a href="<?php echo e(route('admin.succession.promotion-readiness')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.promotion-readiness') ? 'active' : ''); ?>">Promotion Readiness</a>
            <a href="<?php echo e(route('admin.succession.succession-history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.succession-history') ? 'active' : ''); ?>">Succession History</a>
            <a href="<?php echo e(route('admin.succession.talent-pool')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.succession.talent-pool') ? 'active' : ''); ?>">Talent Pool</a>
        </div>

        <div class="nav-section-header">Evaluation & Recognition</div>

        <a href="<?php echo e(route('admin.evaluation.driver-evaluation')); ?>" class="nav-parent <?php echo e(request()->routeIs('admin.evaluation.*') ? 'open' : ''); ?>" data-target="nav-evaluation" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-users"></i>
            <span class="nav-text">Peer-to-Peer Evaluation</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.evaluation.*') ? 'open' : ''); ?>" id="nav-evaluation">
            <a href="<?php echo e(route('admin.evaluation.driver-evaluation')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.driver-evaluation') ? 'active' : ''); ?>">Driver Evaluation</a>
            <a href="<?php echo e(route('admin.evaluation.review')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.review') ? 'active' : ''); ?>">Evaluation Review</a>
            <a href="<?php echo e(route('admin.evaluation.feedback-summary')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.feedback-summary') ? 'active' : ''); ?>">Feedback Summary</a>
            <a href="<?php echo e(route('admin.evaluation.reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.reports') ? 'active' : ''); ?>">Evaluation Reports</a>
            <a href="<?php echo e(route('admin.evaluation.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.analytics') ? 'active' : ''); ?>">Evaluation Analytics</a>
            <a href="<?php echo e(route('admin.evaluation.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.evaluation.history') ? 'active' : ''); ?>">Evaluation History</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.recognition.*') ? 'open' : ''); ?>" data-target="nav-recognition" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-trophy"></i>
            <span class="nav-text">Social Recognition</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.recognition.*') ? 'open' : ''); ?>" id="nav-recognition">
            <a href="<?php echo e(route('admin.recognition.awards')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.awards') ? 'active' : ''); ?>">Awards</a>
            <a href="<?php echo e(route('admin.recognition.badges')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.badges') ? 'active' : ''); ?>">Achievement Badges</a>
            <a href="<?php echo e(route('admin.recognition.leaderboard')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.leaderboard') ? 'active' : ''); ?>">Leaderboard</a>
            <a href="<?php echo e(route('admin.recognition.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.history') ? 'active' : ''); ?>">Recognition History</a>
            <a href="<?php echo e(route('admin.recognition.certificates')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.certificates') ? 'active' : ''); ?>">Certificates & Rewards</a>
            <a href="<?php echo e(route('admin.recognition.analytics')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.recognition.analytics') ? 'active' : ''); ?>">Recognition Analytics</a>
        </div>

        <div class="nav-section-header">Analytics & Reports</div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.reports.*') ? 'open' : ''); ?>" data-target="nav-reports" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-file-alt"></i>
            <span class="nav-text">Reports & Analytics</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.reports.*') ? 'open' : ''); ?>" id="nav-reports">
            <a href="<?php echo e(route('admin.reports.driver-reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.driver-reports') ? 'active' : ''); ?>">Driver Reports</a>
            <a href="<?php echo e(route('admin.reports.evaluation-reports')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.evaluation-reports') ? 'active' : ''); ?>">Evaluation Reports</a>
            <a href="<?php echo e(route('admin.reports.analytics-dashboard')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.analytics-dashboard') ? 'active' : ''); ?>">Analytics Dashboard</a>
            <a href="<?php echo e(route('admin.reports.data-visualization')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.data-visualization') ? 'active' : ''); ?>">Data Visualization</a>
            <a href="<?php echo e(route('admin.reports.export-center')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.export-center') ? 'active' : ''); ?>">Export Center</a>
            <a href="<?php echo e(route('admin.reports.report-history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.reports.report-history') ? 'active' : ''); ?>">Report History</a>
        </div>

        <div class="nav-section-header">System</div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.notifications.*') ? 'open' : ''); ?>" data-target="nav-notifications" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-bell"></i>
            <span class="nav-text">Notifications</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.notifications.*') ? 'open' : ''); ?>" id="nav-notifications">
            <a href="<?php echo e(route('admin.notifications.training')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.training') ? 'active' : ''); ?>">Training Notifications</a>
            <a href="<?php echo e(route('admin.notifications.performance')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.performance') ? 'active' : ''); ?>">Performance Notifications</a>
            <a href="<?php echo e(route('admin.notifications.announcements')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.announcements') ? 'active' : ''); ?>">System Announcements</a>
            <a href="<?php echo e(route('admin.notifications.history')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.history') ? 'active' : ''); ?>">Notification History</a>
            <a href="<?php echo e(route('admin.notifications.settings')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.settings') ? 'active' : ''); ?>">Notification Settings</a>
            <a href="<?php echo e(route('admin.notifications.logs')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.notifications.logs') ? 'active' : ''); ?>">Notification Logs</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.users.*') ? 'open' : ''); ?>" data-target="nav-users" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-user-cog"></i>
            <span class="nav-text">User Management</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.users.*') ? 'open' : ''); ?>" id="nav-users">
            <a href="<?php echo e(route('admin.users.accounts')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.accounts') ? 'active' : ''); ?>">User Accounts</a>
            <a href="<?php echo e(route('admin.users.roles')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.roles') ? 'active' : ''); ?>">User Roles & Permissions</a>
            <a href="<?php echo e(route('admin.users.account-management')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.account-management') ? 'active' : ''); ?>">Account Management</a>
            <a href="<?php echo e(route('admin.users.login-logs')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.login-logs') ? 'active' : ''); ?>">Login & Activity Logs</a>
            <a href="<?php echo e(route('admin.users.security')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.security') ? 'active' : ''); ?>">Security Monitoring</a>
            <a href="<?php echo e(route('admin.users.audit-logs')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.users.audit-logs') ? 'active' : ''); ?>">Audit Logs</a>
        </div>

        <a href="javascript:void(0);" class="nav-parent <?php echo e(request()->routeIs('admin.settings.*') ? 'open' : ''); ?>" data-target="nav-settings" onclick="event.preventDefault(); toggleAccordion(this)">
            <i class="fas fa-cog"></i>
            <span class="nav-text">Settings</span>
            <i class="fas fa-chevron-right chevron"></i>
        </a>
        <div class="nav-submenu <?php echo e(request()->routeIs('admin.settings.*') ? 'open' : ''); ?>" id="nav-settings">
            <a href="<?php echo e(route('admin.settings.agency.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.agency.*') ? 'active' : ''); ?>">Agency Settings</a>
            <a href="<?php echo e(route('admin.settings.preferences.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.preferences.*') ? 'active' : ''); ?>">System Preferences</a>
            <a href="<?php echo e(route('admin.settings.security.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.security.*') ? 'active' : ''); ?>">Security Settings</a>
            <a href="<?php echo e(route('admin.settings.appearance.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.appearance.*') ? 'active' : ''); ?>">Appearance & Localization</a>
            <a href="<?php echo e(route('admin.settings.backup.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.backup.*') ? 'active' : ''); ?>">Backup & Recovery</a>
            <a href="<?php echo e(route('admin.settings.logs.index')); ?>" class="nav-subitem <?php echo e(request()->routeIs('admin.settings.logs.*') ? 'active' : ''); ?>">System Logs</a>
        </div>

        <a href="<?php echo e(route('logout')); ?>" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
        </a>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>
    </nav>

    <div class="user-area">
        <img src="<?php echo e(asset('admin/avatar')); ?>" alt="Admin" class="user-avatar">
        <div class="user-info">
                <div class="user-name"><?php echo e(Auth::user()?->name ?? 'Admin User'); ?></div>
                <div class="user-role"><?php echo e(Auth::user()?->isAdmin() ? 'System Administrator' : 'User'); ?></div>
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
                <input type="text" placeholder="Search drivers, modules...">
            </div>
        </div>

        <div class="navbar-right">
            <div class="datetime" id="datetime"></div>
            <a href="<?php echo e(route('admin.notifications.index')); ?>" class="icon-btn">
                <i class="fas fa-bell"></i>
                <span class="badge"></span>
            </a>
            <a href="#" class="icon-btn">
                <i class="fas fa-envelope"></i>
                <span class="badge"></span>
            </a>
            <div class="profile-dropdown">
                <img src="<?php echo e(asset('admin/avatar')); ?>" alt="Profile" class="profile-img" onclick="toggleDropdown()">
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="#"><i class="fas fa-user"></i> My Profile</a>
                    <a href="<?php echo e(route('admin.settings.index')); ?>"><i class="fas fa-cog"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="dashboard-content">
        <?php if(session('success')): ?>
            <div class="toast-notification success" style="position:fixed;top:20px;right:20px;z-index:3000;">
                <i class="fas fa-check-circle" style="color:var(--success);"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
            <script>
                setTimeout(() => {
                    const el = document.querySelector('.toast-notification.success');
                    if (el) { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s ease'; setTimeout(() => el.remove(), 300); }
                }, 3000);
            </script>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="toast-notification error" style="position:fixed;top:20px;right:20px;z-index:3000;">
                <i class="fas fa-exclamation-circle" style="color:var(--danger);"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <script>
                setTimeout(() => {
                    const el = document.querySelector('.toast-notification.error');
                    if (el) { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s ease'; setTimeout(() => el.remove(), 300); }
                }, 3000);
            </script>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>

<script>
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

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('mobileOverlay').classList.remove('active');
        }
    });

    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('datetime').textContent = now.toLocaleDateString('en-US', options);
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('show');
    }

    function toggleAccordion(el) {
        const targetId = el.getAttribute('data-target');
        const submenu = document.getElementById(targetId);
        const isOpen = el.classList.contains('open');

        document.querySelectorAll('.nav-parent.open').forEach(openEl => {
            openEl.classList.remove('open');
            document.getElementById(openEl.getAttribute('data-target')).classList.remove('open');
        });

        if (!isOpen) {
            el.classList.add('open');
            submenu.classList.add('open');
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = 'toast-notification ' + type;
        toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '" style="color:var(--' + (type === 'success' ? 'success' : 'danger') + ');"></i><span>' + message + '</span>';
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Persist sidebar scroll position across module page loads
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            const savedScrollPos = sessionStorage.getItem('sidebar_scroll_position');
            if (savedScrollPos !== null) {
                sidebar.scrollTop = parseInt(savedScrollPos, 10);
            }
            sidebar.addEventListener('scroll', () => {
                sessionStorage.setItem('sidebar_scroll_position', sidebar.scrollTop);
            });
        }
    });

    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('profileDropdown');
        const profileImg = document.querySelector('.profile-img');
        if (dropdown && !dropdown.contains(e.target) && e.target !== profileImg) {
            dropdown.classList.remove('show');
        }
    });
</script>

<script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</script>

</body>
</html>
<?php /**PATH C:\Users\ADMIN\Herd\TEAM-3\resources\views/admin/layouts/admin.blade.php ENDPATH**/ ?>