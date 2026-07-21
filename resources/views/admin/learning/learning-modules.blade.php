@extends('admin.layouts.admin')

@section('title', 'TripWise — Learning Modules')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.learning.index') }}">Learning Management</a>
    <span>/</span>
    <span>Learning Modules</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Learning Modules</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage all learning materials assigned to drivers.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addModuleModal')"><i class="fas fa-plus"></i> Assign Module</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-book-open"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total_modules'] }}</h3>
            <p>Total Learning Modules</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <h3>{{ $stats['assigned_courses'] }}</h3>
            <p>Assigned Courses</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-play-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active_modules'] }}</h3>
            <p>Active Modules</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed_courses'] }}</h3>
            <p>Completed Courses</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.learning.modules') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search modules..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="road_safety" {{ request('category') === 'road_safety' ? 'selected' : '' }}>Road Safety</option>
                <option value="defensive_driving" {{ request('category') === 'defensive_driving' ? 'selected' : '' }}>Defensive Driving</option>
                <option value="customer_service" {{ request('category') === 'customer_service' ? 'selected' : '' }}>Customer Service</option>
                <option value="company_policies" {{ request('category') === 'company_policies' ? 'selected' : '' }}>Company Policies</option>
                <option value="traffic_rules" {{ request('category') === 'traffic_rules' ? 'selected' : '' }}>Traffic Rules</option>
                <option value="emergency_response" {{ request('category') === 'emergency_response' ? 'selected' : '' }}>Emergency Response</option>
                <option value="vehicle_maintenance" {{ request('category') === 'vehicle_maintenance' ? 'selected' : '' }}>Vehicle Maintenance</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Learning Modules Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-book-open"></i> Learning Modules</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Module ID</th>
                    <th>Module Title</th>
                    <th>Category</th>
                    <th>Assigned Driver</th>
                    <th>Assigned Date</th>
                    <th>Due Date</th>
                    <th>Completion Status</th>
                    <th>Progress</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($modules as $module)
                    <tr>
                        <td>#LRN-{{ str_pad($module->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $module->title }}</strong></td>
                        <td style="text-transform:capitalize;">{{ str_replace('_', ' ', $module->category) }}</td>
                        <td>
                            @php
                                $assignments = \App\Models\LearningAssignment::where('learning_module_id', $module->id)->get();
                                echo $assignments->count() . ' drivers';
                            @endphp
                        </td>
                        <td>{{ $module->created_at->format('M d, Y') }}</td>
                        <td>N/A</td>
                        <td>
                            <span class="item-badge {{ $module->status === 'active' ? 'badge-success' : ($module->status === 'inactive' ? 'badge-warning' : 'badge-info') }}">
                                {{ ucfirst($module->status) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $avgProgress = $assignments->avg('progress_percentage') ?: 0;
                            @endphp
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div class="progress-bar" style="width:100px;height:8px;">
                                    <div class="progress-fill" style="width:{{ $avgProgress }}%;"></div>
                                </div>
                                <span style="font-size:0.85rem;font-weight:600;">{{ round($avgProgress) }}%</span>
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <button class="btn btn-sm btn-secondary" title="View"><i class="fas fa-eye"></i></button>
                                <button class="btn btn-sm btn-primary" title="Assign Module"><i class="fas fa-plus"></i></button>
                                <button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" title="Archive"><i class="fas fa-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">No learning modules found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $modules->links() }}
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Learning Progress</h3>
        <div class="chart-wrapper">
            <canvas id="learningProgressChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Module Completion</h3>
        <div class="chart-wrapper">
            <canvas id="moduleCompletionChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
    };

    new Chart(document.getElementById('learningProgressChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Completion %',
                data: [45, 52, 58, 65, 72, 78],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,0.1)',
                fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10b981'
            }]
        },
        options: { ...chartDefaults, plugins: { legend: { display: false } } }
    });

    new Chart(document.getElementById('moduleCompletionChart'), {
        type: 'bar',
        data: {
            labels: ['Road Safety', 'Defensive Driving', 'Customer Service', 'Company Policies', 'Traffic Rules'],
            datasets: [{
                label: 'Completed',
                data: [85, 72, 68, 90, 55],
                backgroundColor: '#3b82f6',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });
});
</script>
@endsection
