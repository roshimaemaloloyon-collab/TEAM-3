@extends('admin.layouts.admin')

@section('title', 'TripWise — Performance Management')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Performance Management</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance Management</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Monitor, evaluate and analyze driver performance using KPIs, customer ratings, attendance, peer evaluations and safety metrics.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
        <button class="btn btn-primary" onclick="openModal('addReviewModal')"><i class="fas fa-plus"></i> New Monthly Review</button>
    </div>
</div>

<!-- Advanced Filter Toolbar -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <div class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <select id="filterMonth" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:130px;">
            <option value="">All Months</option>
            <option>January</option><option>February</option><option>March</option>
            <option>April</option><option>May</option><option>June</option>
            <option>July</option><option>August</option><option>September</option>
            <option>October</option><option>November</option><option>December</option>
        </select>
        <select id="filterQuarter" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:130px;">
            <option value="">All Quarters</option>
            <option>Q1</option><option>Q2</option><option>Q3</option><option>Q4</option>
        </select>
        <select id="filterYear" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:110px;">
            <option value="">All Years</option>
            <option>2026</option><option>2025</option><option>2024</option>
        </select>
        <select id="filterBranch" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Branches</option>
            <option>North Branch</option><option>South Branch</option>
            <option>East Branch</option><option>West Branch</option><option>Central Branch</option>
        </select>
        <select id="filterVehicle" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Vehicle Types</option>
            <option>Sedan</option><option>SUV</option><option>Van</option><option>Motorcycle</option>
        </select>
        <select id="filterDriver" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Drivers</option>
            <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button class="btn btn-primary" onclick="applyFilters()" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-secondary" onclick="resetFilters()" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </div>
</div>

<!-- Compact Navigation Tabs -->
<div style="margin-bottom:1.5rem;">
    <nav class="perf-tabs-list" id="perfTabNav" role="tablist" aria-label="Performance sections">
        <button class="perf-tab-trigger active" data-tab="tab-customer" role="tab" aria-selected="true">
            <i class="fas fa-star" style="font-size:0.9rem;"></i>
            <span>Customer Ratings</span>
        </button>
        <button class="perf-tab-trigger" data-tab="tab-peer" role="tab" aria-selected="false">
            <i class="fas fa-users" style="font-size:0.9rem;"></i>
            <span>Peer Evaluations</span>
        </button>
        <button class="perf-tab-trigger" data-tab="tab-attendance" role="tab" aria-selected="false">
            <i class="fas fa-calendar-check" style="font-size:0.9rem;"></i>
            <span>Attendance</span>
        </button>
        <button class="perf-tab-trigger" data-tab="tab-trips" role="tab" aria-selected="false">
            <i class="fas fa-route" style="font-size:0.9rem;"></i>
            <span>Trip Completion</span>
        </button>
        <button class="perf-tab-trigger" data-tab="tab-safety" role="tab" aria-selected="false">
            <i class="fas fa-shield-alt" style="font-size:0.9rem;"></i>
            <span>Safety Score</span>
        </button>
        <button class="perf-tab-trigger" data-tab="tab-complaints" role="tab" aria-selected="false">
            <i class="fas fa-exclamation-triangle" style="font-size:0.9rem;"></i>
            <span>Complaints</span>
        </button>
        <div style="position:relative;" id="moreDropdownWrapper">
            <button class="perf-tab-trigger" id="moreDropdownBtn" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-h" style="font-size:0.9rem;"></i>
                <span>More</span>
                <i class="fas fa-chevron-down" style="font-size:0.75rem;margin-left:0.25rem;"></i>
            </button>
            <div id="moreDropdown" style="display:none;position:absolute;top:calc(100% + 0.6rem);left:0;z-index:50;background:var(--white);border:1px solid var(--border);border-radius:0.85rem;box-shadow:0 12px 30px rgba(0,0,0,0.12);min-width:200px;overflow:hidden;">
                <button data-tab="tab-commendations" class="more-dropdown-item" style="display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;border:none;background:none;color:var(--text-dark);font-size:0.85rem;cursor:pointer;transition:background 0.2s;font-family:'Poppins',sans-serif;text-align:left;"><i class="fas fa-medal" style="color:#d97706;width:16px;"></i> Commendations</button>
                <button data-tab="tab-kpi" class="more-dropdown-item" style="display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;border:none;background:none;color:var(--text-dark);font-size:0.85rem;cursor:pointer;transition:background 0.2s;font-family:'Poppins',sans-serif;text-align:left;"><i class="fas fa-bullseye" style="color:#7c3aed;width:16px;"></i> KPI Monitoring</button>
                <button data-tab="tab-ranking" class="more-dropdown-item" style="display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;border:none;background:none;color:var(--text-dark);font-size:0.85rem;cursor:pointer;transition:background 0.2s;font-family:'Poppins',sans-serif;text-align:left;"><i class="fas fa-ranking-star" style="color:#6366f1;width:16px;"></i> Performance Ranking</button>
                <button data-tab="tab-history" class="more-dropdown-item" style="display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;border:none;background:none;color:var(--text-dark);font-size:0.85rem;cursor:pointer;transition:background 0.2s;font-family:'Poppins',sans-serif;text-align:left;"><i class="fas fa-history" style="color:#ec4899;width:16px;"></i> Performance History</button>
                <button data-tab="tab-reviews" class="more-dropdown-item" style="display:flex;align-items:center;gap:0.75rem;width:100%;padding:0.75rem 1rem;border:none;background:none;color:var(--text-dark);font-size:0.85rem;cursor:pointer;transition:background 0.2s;font-family:'Poppins',sans-serif;text-align:left;"><i class="fas fa-clipboard-check" style="color:#d97706;width:16px;"></i> Monthly Reviews</button>
            </div>
        </div>
    </nav>
</div>

<!-- Tab Contents -->
<div class="perf-tab-wrapper">

    <!-- Customer Ratings Tab -->
    <div id="tab-customer" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-star"></i> Recent Customer Ratings</h3>
                <button class="btn btn-primary" onclick="openModal('addRatingModal')"><i class="fas fa-plus"></i> Add Rating</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Trip ID</th>
                            <th>Rating</th>
                            <th>Review Date</th>
                            <th>Comments</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#92400e;flex-shrink:0;">JD</div><strong>Juan Dela Cruz</strong></div></td>
                            <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#TRP-2026-1001</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.25rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><strong style="margin-left:0.4rem;color:var(--text-dark);">5.0</strong></div></td>
                            <td>Jan 15, 2026</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">Excellent service, very punctual.</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View rating')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit rating')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete rating')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#1e40af;flex-shrink:0;">MS</div><strong>Maria Santos</strong></div></td>
                            <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#TRP-2026-1002</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.25rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star-half-alt" style="color:#f59e0b;font-size:0.8rem;"></i><strong style="margin-left:0.4rem;color:var(--text-dark);">4.8</strong></div></td>
                            <td>Jan 15, 2026</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">Great driver, smooth ride.</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View rating')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit rating')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete rating')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#166534;flex-shrink:0;">PR</div><strong>Pedro Reyes</strong></div></td>
                            <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#TRP-2026-1003</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.25rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star-half-alt" style="color:#f59e0b;font-size:0.8rem;"></i><strong style="margin-left:0.4rem;color:var(--text-dark);">4.5</strong></div></td>
                            <td>Jan 14, 2026</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">Good overall, minor delay.</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View rating')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit rating')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete rating')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#fdf4ff;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#7c3aed;flex-shrink:0;">AL</div><strong>Ana Lim</strong></div></td>
                            <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#TRP-2026-1004</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.25rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><strong style="margin-left:0.4rem;color:var(--text-dark);">4.9</strong></div></td>
                            <td>Jan 14, 2026</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">Perfect ride! Very professional.</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View rating')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit rating')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete rating')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#fff7ed;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#c2410c;flex-shrink:0;">RG</div><strong>Rosa Garcia</strong></div></td>
                            <td><span style="font-size:0.8rem;color:var(--text-muted);font-family:monospace;">#TRP-2026-1005</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.25rem;"><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="fas fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="far fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><i class="far fa-star" style="color:#f59e0b;font-size:0.8rem;"></i><strong style="margin-left:0.4rem;color:var(--text-dark);">3.2</strong></div></td>
                            <td>Jan 13, 2026</td>
                            <td style="font-size:0.85rem;color:var(--text-muted);">Okay ride, could improve on route timing.</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View rating')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit rating')"><i class="fas fa-edit"></i></button>
                                <button class="icon-btn" title="Delete" onclick="showToast('Delete rating')" style="color:var(--danger);"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-chart-pie"></i> Customer Rating Distribution</h3>
                <div class="chart-wrapper">
                    <canvas id="ratingDistChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Peer Evaluations Tab -->
    <div id="tab-peer" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-users"></i> Peer Evaluation Scores</h3>
                <button class="btn btn-primary" onclick="openModal('addPeerModal')"><i class="fas fa-plus"></i> Add Evaluation</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Evaluator</th>
                            <th>Teamwork</th>
                            <th>Professionalism</th>
                            <th>Communication</th>
                            <th>Safety Awareness</th>
                            <th>Reliability</th>
                            <th>Overall Peer Score</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#92400e;flex-shrink:0;">JD</div><strong>Juan Dela Cruz</strong></div></td>
                            <td><span style="font-size:0.85rem;color:var(--text-muted);">Maria Santos</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.9</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.8</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.7</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.9</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.8</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div style="width:60px;height:8px;background:var(--beige-dark);border-radius:999px;overflow:hidden;"><div style="width:96%;height:100%;background:#10b981;border-radius:999px;"></div></div><strong style="color:#10b981;">4.82</strong><span class="status-badge status-success" style="font-size:0.7rem;">Excellent</span></div></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View evaluation')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit evaluation')"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#1e40af;flex-shrink:0;">MS</div><strong>Maria Santos</strong></div></td>
                            <td><span style="font-size:0.85rem;color:var(--text-muted);">Pedro Reyes</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.7</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.9</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.8</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#3b82f6;">4.6</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.9</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div style="width:60px;height:8px;background:var(--beige-dark);border-radius:999px;overflow:hidden;"><div style="width:95%;height:100%;background:#3b82f6;border-radius:999px;"></div></div><strong style="color:#3b82f6;">4.78</strong><span class="status-badge status-success" style="font-size:0.7rem;">Very Good</span></div></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View evaluation')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit evaluation')"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><div style="display:flex;align-items:center;gap:0.6rem;"><div style="width:32px;height:32px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#166534;flex-shrink:0;">PR</div><strong>Pedro Reyes</strong></div></td>
                            <td><span style="font-size:0.85rem;color:var(--text-muted);">Ana Lim</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#3b82f6;">4.6</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.7</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#3b82f6;">4.5</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#f59e0b;">4.4</span></td>
                            <td><span style="font-size:0.85rem;font-weight:600;color:#10b981;">4.8</span></td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div style="width:60px;height:8px;background:var(--beige-dark);border-radius:999px;overflow:hidden;"><div style="width:92%;height:100%;background:#f59e0b;border-radius:999px;"></div></div><strong style="color:#f59e0b;">4.60</strong><span class="status-badge status-warning" style="font-size:0.7rem;">Good</span></div></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View evaluation')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit evaluation')"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-chart-area"></i> Peer Evaluation Score Trend</h3>
                <div class="chart-wrapper">
                    <canvas id="peerTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Tab -->
    <div id="tab-attendance" class="perf-page-tab" style="display:none;">
        <div class="table-card" style="margin-bottom:1.5rem;">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-calendar-check"></i> Attendance Overview</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;">
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">238</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Present</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--warning);margin:0;">5</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Late</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--danger);margin:0;">2</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Absent</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--info);margin:0;">3</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">On Leave</p>
                </div>
            </div>
        </div>
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-list"></i> Attendance Records</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Present</th>
                            <th>Late</th>
                            <th>Absent</th>
                            <th>Leave</th>
                            <th>Working Days</th>
                            <th>Attendance %</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>20</td><td>1</td><td>0</td><td>0</td>
                            <td>21</td>
                            <td><strong>95.2%</strong></td>
                            <td><span class="status-badge status-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><strong>Maria Santos</strong></td>
                            <td>19</td><td>2</td><td>0</td><td>0</td>
                            <td>21</td>
                            <td><strong>90.5%</strong></td>
                            <td><span class="status-badge status-success">Good</span></td>
                        </tr>
                        <tr>
                            <td><strong>Pedro Reyes</strong></td>
                            <td>18</td><td>1</td><td>1</td><td>1</td>
                            <td>21</td>
                            <td><strong>85.7%</strong></td>
                            <td><span class="status-badge status-warning">Needs Improvement</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Trip Completion Tab -->
    <div id="tab-trips" class="perf-page-tab" style="display:none;">
        <div class="table-card" style="margin-bottom:1.5rem;">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-route"></i> Trip Completion Overview</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">1,248</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Completed Trips</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">1,320</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Assigned Trips</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">94.5%</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Completion Rate</p>
                </div>
            </div>
        </div>
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-list"></i> Driver Trip Summary</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Driver</th><th>Assigned</th><th>Completed</th><th>Cancelled</th><th>Missed</th><th>Completion Rate</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>120</td><td>115</td><td>3</td><td>2</td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div class="progress-bar" style="width:120px;height:8px;"><div class="progress-fill" style="width:96%;"></div></div><span style="font-size:0.85rem;font-weight:600;">96%</span></div></td>
                            <td><span class="status-badge status-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><strong>Maria Santos</strong></td>
                            <td>115</td><td>108</td><td>5</td><td>2</td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div class="progress-bar" style="width:120px;height:8px;"><div class="progress-fill" style="width:94%;"></div></div><span style="font-size:0.85rem;font-weight:600;">94%</span></div></td>
                            <td><span class="status-badge status-success">Good</span></td>
                        </tr>
                        <tr>
                            <td><strong>Pedro Reyes</strong></td>
                            <td>110</td><td>98</td><td>8</td><td>4</td>
                            <td><div style="display:flex;align-items:center;gap:0.5rem;"><div class="progress-bar" style="width:120px;height:8px;"><div class="progress-fill" style="width:89%;"></div></div><span style="font-size:0.85rem;font-weight:600;">89%</span></div></td>
                            <td><span class="status-badge status-warning">Needs Improvement</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Safety Score Tab -->
    <div id="tab-safety" class="perf-page-tab" style="display:none;">
        <div class="table-card" style="margin-bottom:1.5rem;">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-shield-alt"></i> Safety Score Overview</h3>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">4.8/5</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Average Safety Score</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">2</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Traffic Violations</p>
                </div>
                <div style="background:var(--beige);padding:1rem;border-radius:0.75rem;text-align:center;">
                    <p style="font-size:1.5rem;font-weight:700;color:var(--primary);margin:0;">0</p>
                    <p style="font-size:0.8rem;color:var(--text-muted);">Accidents</p>
                </div>
            </div>
        </div>
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-list"></i> Driver Safety Scores</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Driver</th><th>Safety Score</th><th>Violations</th><th>Accidents</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td><strong>4.9/5</strong></td>
                            <td>0</td><td>0</td>
                            <td><span class="status-badge status-success">Excellent</span></td>
                        </tr>
                        <tr>
                            <td><strong>Maria Santos</strong></td>
                            <td><strong>4.8/5</strong></td>
                            <td>1</td><td>0</td>
                            <td><span class="status-badge status-success">Good</span></td>
                        </tr>
                        <tr>
                            <td><strong>Pedro Reyes</strong></td>
                            <td><strong>4.6/5</strong></td>
                            <td>1</td><td>0</td>
                            <td><span class="status-badge status-warning">Needs Improvement</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-shield-alt"></i> Safety Score Trend</h3>
                <div class="chart-wrapper">
                    <canvas id="safetyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints Tab -->
    <div id="tab-complaints" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-exclamation-triangle"></i> Complaints Management</h3>
                <button class="btn btn-primary" onclick="openModal('addComplaintModal')"><i class="fas fa-plus"></i> Add Complaint</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Complaint ID</th><th>Driver</th><th>Customer</th><th>Type</th><th>Date</th><th>Status</th><th style="text-align:center;">Actions</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#CMP-001</td>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>Customer A</td>
                            <td>Rude Behavior</td>
                            <td>Jan 12, 2026</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View complaint')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Resolve" onclick="showToast('Resolve complaint')" style="color:var(--success);"><i class="fas fa-check"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#CMP-002</td>
                            <td><strong>Maria Santos</strong></td>
                            <td>Customer B</td>
                            <td>Late Arrival</td>
                            <td>Jan 10, 2026</td>
                            <td><span class="status-badge status-success">Resolved</span></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View complaint')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Reopen" onclick="showToast('Reopen complaint')"><i class="fas fa-undo"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#CMP-003</td>
                            <td><strong>Pedro Reyes</strong></td>
                            <td>Customer C</td>
                            <td>Vehicle Cleanliness</td>
                            <td>Jan 08, 2026</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View complaint')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Resolve" onclick="showToast('Resolve complaint')" style="color:var(--success);"><i class="fas fa-check"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-comments"></i> Complaints vs Commendations</h3>
                <div class="chart-wrapper">
                    <canvas id="complaintChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Commendations Tab -->
    <div id="tab-commendations" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-medal"></i> Commendations</h3>
                <button class="btn btn-primary" onclick="openModal('addCommendationModal')"><i class="fas fa-plus"></i> Add Commendation</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Commendation ID</th><th>Driver</th><th>Customer</th><th>Reason</th><th>Date</th><th style="text-align:center;">Actions</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#CMD-001</td>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>Customer A</td>
                            <td>Exceptional Service</td>
                            <td>Jan 15, 2026</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View commendation')"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#CMD-002</td>
                            <td><strong>Ana Lim</strong></td>
                            <td>Customer B</td>
                            <td>Professionalism</td>
                            <td>Jan 14, 2026</td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View commendation')"><i class="fas fa-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- KPI Monitoring Tab -->
    <div id="tab-kpi" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-bullseye"></i> KPI Monitoring</h3>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.85rem;font-weight:600;">Customer Rating</span><span style="font-size:0.85rem;font-weight:600;color:var(--primary);">4.7/5</span></div>
                    <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:94%;"></div></div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.85rem;font-weight:600;">Peer Evaluation</span><span style="font-size:0.85rem;font-weight:600;color:var(--primary);">4.5/5</span></div>
                    <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:90%;"></div></div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.85rem;font-weight:600;">Attendance Rate</span><span style="font-size:0.85rem;font-weight:600;color:var(--primary);">96%</span></div>
                    <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:96%;"></div></div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.85rem;font-weight:600;">Trip Completion</span><span style="font-size:0.85rem;font-weight:600;color:var(--primary);">94.5%</span></div>
                    <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:94.5%;"></div></div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;"><span style="font-size:0.85rem;font-weight:600;">Safety Score</span><span style="font-size:0.85rem;font-weight:600;color:var(--primary);">4.8/5</span></div>
                    <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:96%;"></div></div>
                </div>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-chart-bar"></i> KPI Distribution</h3>
                <div class="chart-wrapper">
                    <canvas id="kpiDistChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-building"></i> Performance Comparison by Branch</h3>
                <div class="chart-wrapper">
                    <canvas id="branchChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Ranking Tab -->
    <div id="tab-ranking" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-ranking-star"></i> Full Driver Performance Ranking</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Rank</th><th>Driver</th><th>Overall Score</th><th>Customer Rating</th><th>Peer Score</th><th>Safety</th><th>Attendance</th><th>Completion</th><th>Trend</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#fef3c7;color:#92400e;font-weight:700;font-size:0.85rem;">1</span></td>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td><strong>4.9</strong></td>
                            <td>5.0</td><td>4.8</td><td>4.9</td><td>95%</td><td>96%</td>
                            <td><i class="fas fa-arrow-up" style="color:var(--success);"></i></td>
                        </tr>
                        <tr>
                            <td><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#dbeafe;color:#1e40af;font-weight:700;font-size:0.85rem;">2</span></td>
                            <td><strong>Maria Santos</strong></td>
                            <td><strong>4.8</strong></td>
                            <td>4.8</td><td>4.7</td><td>4.8</td><td>91%</td><td>94%</td>
                            <td><i class="fas fa-minus" style="color:var(--text-muted);"></i></td>
                        </tr>
                        <tr>
                            <td><span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#f0fdf4;color:#166534;font-weight:700;font-size:0.85rem;">3</span></td>
                            <td><strong>Pedro Reyes</strong></td>
                            <td><strong>4.6</strong></td>
                            <td>4.5</td><td>4.6</td><td>4.6</td><td>86%</td><td>89%</td>
                            <td><i class="fas fa-arrow-down" style="color:var(--danger);"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-ranking-star"></i> Driver Performance Ranking</h3>
                <div class="chart-wrapper">
                    <canvas id="rankingChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance History Tab -->
    <div id="tab-history" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <h3 style="margin:0 0 1rem;"><i class="fas fa-history"></i> Performance History</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Period</th><th>Driver</th><th>Overall Score</th><th>Customer Rating</th><th>Peer Score</th><th>Safety</th><th>Attendance</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Q4 2025</strong></td>
                            <td>Juan Dela Cruz</td>
                            <td><strong>4.8</strong></td>
                            <td>4.9</td><td>4.7</td><td>4.8</td><td>94%</td>
                        </tr>
                        <tr>
                            <td><strong>Q3 2025</strong></td>
                            <td>Juan Dela Cruz</td>
                            <td><strong>4.7</strong></td>
                            <td>4.8</td><td>4.6</td><td>4.7</td><td>93%</td>
                        </tr>
                        <tr>
                            <td><strong>Q4 2025</strong></td>
                            <td>Maria Santos</td>
                            <td><strong>4.7</strong></td>
                            <td>4.7</td><td>4.6</td><td>4.8</td><td>90%</td>
                        </tr>
                        <tr>
                            <td><strong>Q3 2025</strong></td>
                            <td>Maria Santos</td>
                            <td><strong>4.6</strong></td>
                            <td>4.6</td><td>4.5</td><td>4.7</td><td>89%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="charts-grid" style="margin-top:1.5rem;">
            <div class="chart-card">
                <h3><i class="fas fa-chart-line"></i> Monthly Performance Trend</h3>
                <div class="chart-wrapper">
                    <canvas id="perfTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Reviews Tab -->
    <div id="tab-reviews" class="perf-page-tab" style="display:none;">
        <div class="table-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
                <h3 style="margin:0;"><i class="fas fa-clipboard-check"></i> Monthly Performance Reviews</h3>
                <button class="btn btn-primary" onclick="openModal('addReviewModal')"><i class="fas fa-plus"></i> Create Review</button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>Review ID</th><th>Driver</th><th>Period</th><th>Overall Score</th><th>Status</th><th style="text-align:center;">Actions</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#REV-001</td>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>Jan 2026</td>
                            <td><strong>4.9/5</strong></td>
                            <td><span class="status-badge status-success">Completed</span></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View review')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit review')"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#REV-002</td>
                            <td><strong>Maria Santos</strong></td>
                            <td>Jan 2026</td>
                            <td><strong>4.7/5</strong></td>
                            <td><span class="status-badge status-success">Completed</span></td>
                            <td style="text-align:center;">
                                <button class="icon-btn" title="View" onclick="showToast('View review')"><i class="fas fa-eye"></i></button>
                                <button class="icon-btn" title="Edit" onclick="showToast('Edit review')"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Add Rating Modal -->
<div id="addRatingModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add Customer Rating</h2>
            <button onclick="closeModal('addRatingModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Rating</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>5 Stars</option><option>4 Stars</option><option>3 Stars</option><option>2 Stars</option><option>1 Star</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Comments</label>
                <textarea rows="3" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addRatingModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveRating()">Save Rating</button>
        </div>
    </div>
</div>

<!-- Add Peer Evaluation Modal -->
<div id="addPeerModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add Peer Evaluation</h2>
            <button onclick="closeModal('addPeerModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Teamwork</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Professionalism</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Communication</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Safety Awareness</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Reliability</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addPeerModal')">Cancel</button>
            <button class="btn btn-primary" onclick="savePeer()">Save Evaluation</button>
        </div>
    </div>
</div>

<!-- Add Complaint Modal -->
<div id="addComplaintModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add Complaint</h2>
            <button onclick="closeModal('addComplaintModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Complaint Type</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Rude Behavior</option><option>Late Arrival</option><option>Vehicle Cleanliness</option><option>Route Issue</option><option>Other</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Description</label>
                <textarea rows="3" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addComplaintModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveComplaint()">Save Complaint</button>
        </div>
    </div>
</div>

<!-- Add Commendation Modal -->
<div id="addCommendationModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Add Commendation</h2>
            <button onclick="closeModal('addCommendationModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Reason</label>
                <textarea rows="3" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addCommendationModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveCommendation()">Save Commendation</button>
        </div>
    </div>
</div>

<!-- Add Monthly Review Modal -->
<div id="addReviewModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Create Monthly Review</h2>
            <button onclick="closeModal('addReviewModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.25rem 1.5rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>Juan Dela Cruz</option><option>Maria Santos</option><option>Pedro Reyes</option><option>Ana Lim</option><option>Rosa Garcia</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Month</label>
                <select style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                    <option>January 2026</option><option>December 2025</option><option>November 2025</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Overall Score</label>
                <input type="number" min="1" max="5" step="0.1" value="4.5" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Recommendations</label>
                <textarea rows="3" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
            </div>
        </div>
        <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
            <button class="btn btn-secondary" onclick="closeModal('addReviewModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveReview()">Save Review</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:var(--charcoal);color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;box-shadow:0 8px 20px rgba(0,0,0,0.2);z-index:3000;align-items:center;gap:0.75rem;font-size:0.85rem;font-family:'Inter',sans-serif;">
    <i class="fas fa-check-circle" style="color:var(--success);"></i>
    <span id="toastMessage"></span>
</div>

@endsection

@section('scripts')
<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function switchPerfTab(btn, tabId) {
    document.querySelectorAll('.perf-tab-trigger').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.perf-page-tab').forEach(t => t.style.display = 'none');
    if (btn) btn.classList.add('active');
    const target = document.getElementById(tabId);
    if (target) target.style.display = 'block';
}

function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}

function saveRating() { closeModal('addRatingModal'); showToast('Customer rating saved.'); }
function savePeer() { closeModal('addPeerModal'); showToast('Peer evaluation saved.'); }
function saveComplaint() { closeModal('addComplaintModal'); showToast('Complaint recorded.'); }
function saveCommendation() { closeModal('addCommendationModal'); showToast('Commendation added.'); }
function saveReview() { closeModal('addReviewModal'); showToast('Monthly review created.'); }

function applyFilters() { showToast('Filters applied.'); }
function resetFilters() { showToast('Filters reset.'); }
function exportReport(format) { showToast('Exporting ' + format.toUpperCase() + ' report...'); }

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

const style = document.createElement('style');
style.textContent = `
    .perf-tabs-list {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        overflow-x: auto;
        scrollbar-width: none;
    }
    .perf-tabs-list::-webkit-scrollbar { display: none; }

    .perf-tab-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        height: 38px;
        padding: 0 0.95rem;
        border-radius: 0.625rem;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #374151;
        cursor: pointer;
        white-space: nowrap;
        font-family: 'Poppins', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        appearance: none;
        -webkit-appearance: none;
        line-height: 1;
    }
    .perf-tab-trigger:hover {
        background: #fff5f5;
        border-color: #fca5a5;
        color: #dc2626;
    }
    .perf-tab-trigger:hover i { color: #dc2626; }
    .perf-tab-trigger.active {
        background: #FF5A4E;
        border-color: #FF5A4E;
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(244,67,54,0.35);
    }
    .perf-tab-trigger.active i { color: #ffffff; }
    .perf-tab-trigger i { font-size: 0.9rem; color: #6b7280; transition: color 0.2s ease; }

    #moreDropdownWrapper .perf-tab-trigger[aria-expanded="true"] {
        background: #fff5f5;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .perf-page-tab { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
`;
document.head.appendChild(style);

document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#f1f5f9' } }
        }
    };

    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul'];
    const primary = '#F44336';

    new Chart(document.getElementById('perfTrendChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Avg Score',
                data: [4.2,4.3,4.4,4.3,4.5,4.6,4.6],
                borderColor: primary,
                backgroundColor: 'rgba(244,67,54,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: primary
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('kpiDistChart'), {
        type: 'bar',
        data: {
            labels: ['Customer Rating','Peer Eval','Attendance','Trip Completion','Safety','Complaints','Training'],
            datasets: [{
                label: 'KPI Score',
                data: [4.7,4.5,0.96,0.945,4.8,0.5,0.85],
                backgroundColor: primary,
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5.0 } } }
    });

    new Chart(document.getElementById('ratingDistChart'), {
        type: 'pie',
        data: {
            labels: ['5 Stars','4 Stars','3 Stars','2 Stars','1 Star'],
            datasets: [{
                data: [65,20,10,3,2],
                backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444','#991b1b']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });

    new Chart(document.getElementById('rankingChart'), {
        type: 'bar',
        data: {
            labels: ['Juan Dela Cruz','Maria Santos','Pedro Reyes','Ana Lim','Rosa Garcia'],
            datasets: [{
                label: 'Overall Score',
                data: [4.9,4.8,4.6,4.5,4.3],
                backgroundColor: primary,
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, max: 5.0 } } }
    });

    new Chart(document.getElementById('branchChart'), {
        type: 'bar',
        data: {
            labels: ['North','South','East','West','Central'],
            datasets: [
                { label: 'Avg Score', data: [4.7,4.6,4.5,4.4,4.2], backgroundColor: primary, borderRadius: 8 },
                { label: 'Avg Safety', data: [4.8,4.7,4.6,4.5,4.4], backgroundColor: '#3b82f6', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 5.0 } } }
    });

    new Chart(document.getElementById('safetyChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Safety Score',
                data: [4.7,4.8,4.7,4.6,4.8,4.8,4.8],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#3b82f6'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('complaintChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Complaints', data: [2,1,3,1,2,2], backgroundColor: '#ef4444', borderRadius: 8 },
                { label: 'Commendations', data: [5,4,6,3,5,4], backgroundColor: '#10b981', borderRadius: 8 }
            ]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('peerTrendChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Peer Score',
                data: [4.4,4.5,4.5,4.4,4.5,4.5,4.5],
                borderColor: '#f59e0b',
                backgroundColor: 'rgba(245,158,11,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#f59e0b'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    document.querySelectorAll('.perf-tab-trigger').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            if (!tabId) return;
            switchPerfTab(this, tabId);
            document.getElementById('moreDropdown').style.display = 'none';
            
            setTimeout(() => {
                const canvas = document.querySelector('#' + tabId + ' canvas');
                if (canvas && canvas.chart) {
                    canvas.chart.resize();
                }
            }, 100);
        });
    });

    document.querySelectorAll('.more-dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            if (!tabId) return;
            document.querySelectorAll('.perf-tab-trigger').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.perf-page-tab').forEach(t => t.style.display = 'none');
            const target = document.getElementById(tabId);
            if (target) target.style.display = 'block';
            document.getElementById('moreDropdown').style.display = 'none';
            
            setTimeout(() => {
                const canvas = target?.querySelector('canvas');
                if (canvas && canvas.chart) {
                    canvas.chart.resize();
                }
            }, 100);
        });
    });

    const moreBtn = document.getElementById('moreDropdownBtn');
    const moreDropdown = document.getElementById('moreDropdown');
    if (moreBtn && moreDropdown) {
        moreBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            moreDropdown.style.display = moreDropdown.style.display === 'none' ? 'block' : 'none';
        });
    }

    document.addEventListener('click', function(e) {
        if (moreDropdown && !moreDropdown.contains(e.target) && e.target !== moreBtn) {
            moreDropdown.style.display = 'none';
        }
    });
});
</script>
@endsection
