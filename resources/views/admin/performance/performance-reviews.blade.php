@extends('admin.layouts.admin')

@section('title', 'TripWise — Performance Reviews')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.performance.index') }}">Performance Management</a>
    <span>/</span>
    <span>Performance Reviews</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Performance Reviews</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Manage periodic driver performance evaluations.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addReviewModal')"><i class="fas fa-plus"></i> Create Review</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Reviews Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['pending'] }}</h3>
            <p>Pending Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar"></i></div>
        <div class="card-info">
            <h3>{{ $stats['monthly'] }}</h3>
            <p>Monthly Reviews</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['quarterly'] }}</h3>
            <p>Quarterly Reviews</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.performance.reviews') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search Driver</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Type</label>
            <select name="type" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Types</option>
                <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="quarterly" {{ request('type') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                <option value="annual" {{ request('type') === 'annual' ? 'selected' : '' }}>Annual</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Reviews Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-clipboard-check"></i> Performance Reviews</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Review Type</th>
                    <th>Review Date</th>
                    <th>Performance Score</th>
                    <th>Reviewer</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($drivers as $driver)
                    @php
                        $isCompleted = $driver->status === 'active';
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('admin.drivers.profile', $driver->id) }}" style="display:flex;align-items:center;gap:0.5rem;color:inherit;text-decoration:none;">
                                <img src="{{ $driver->photo ?: asset('drivers/photo/' . $driver->id) }}" alt="{{ $driver->first_name }}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                <div>
                                    <strong>{{ $driver->full_name }}</strong>
                                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $driver->formatted_id }}</div>
                                </div>
                            </a>
                        </td>
                        <td style="text-transform:capitalize;">Monthly Review</td>
                        <td>{{ $driver->updated_at ? $driver->updated_at->format('M d, Y') : 'Aug 10, 2026' }}</td>
                        <td><strong>{{ number_format($driver->performance_score ?? 4.5, 1) }}/5</strong></td>
                        <td>Operations Admin</td>
                        <td>
                            <span class="item-badge {{ $isCompleted ? 'badge-success' : 'badge-warning' }}">
                                {{ $isCompleted ? 'Completed' : 'Pending Review' }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <a href="{{ route('admin.drivers.profile', $driver->id) }}" class="btn btn-sm btn-secondary" title="View Profile"><i class="fas fa-eye"></i></a>
                                <button type="button" class="btn btn-sm btn-primary edit-review-btn" title="Edit Review In-Place" data-id="{{ $driver->id }}" data-name="{{ $driver->full_name }}" data-score="{{ $driver->performance_score ?? 4.5 }}" data-status="{{ $driver->status }}"><i class="fas fa-edit"></i> Edit</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $drivers->links() }}
    </div>
</div>

<!-- Toast -->
<div id="toast" style="display:none;position:fixed;bottom:1.5rem;right:1.5rem;background:var(--charcoal);color:#fff;padding:0.75rem 1.25rem;border-radius:0.75rem;box-shadow:0 8px 20px rgba(0,0,0,0.2);z-index:3000;align-items:center;gap:0.75rem;font-size:0.85rem;font-family:'Inter',sans-serif;">
    <i class="fas fa-check-circle" style="color:var(--success);"></i>
    <span id="toastMessage"></span>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Review Completion</h3>
        <div class="chart-wrapper">
            <canvas id="reviewChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-line"></i> Performance Review Trend</h3>
        <div class="chart-wrapper">
            <canvas id="reviewTrendChart"></canvas>
        </div>
    </div>
</div>

<!-- Add Review Modal -->
<div id="addReviewModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form action="{{ route('admin.performance.reviews.store') }}" method="POST">
            @csrf
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Create Performance Review</h2>
                <button type="button" onclick="closeModal('addReviewModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Select Driver</label>
                    <select name="driver_id" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        @foreach($allDriversList as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }} ({{ $d->formatted_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Period / Type</label>
                    <select name="review_type" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="monthly">Monthly Review</option>
                        <option value="quarterly">Quarterly Evaluation</option>
                        <option value="annual">Annual Appraisal</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Performance Score (1.0 to 5.0)</label>
                    <input type="number" name="performance_score" min="1" max="5" step="0.1" value="4.5" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Status</label>
                    <select name="status" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="active">Completed</option>
                        <option value="review">Pending Review</option>
                    </select>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Review Remarks & Recommendations</label>
                    <textarea name="remarks" rows="3" placeholder="Enter evaluation feedback..." style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);resize:vertical;"></textarea>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('addReviewModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Review Modal -->
<div id="editReviewModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;">
    <div class="modal-box" style="background:var(--white);border-radius:1rem;width:90%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
        <form id="editReviewForm" method="POST">
            @csrf
            @method('PUT')
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Performance Review</h2>
                <button type="button" onclick="closeModal('editReviewModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;padding:0.25rem;"><i class="fas fa-times"></i></button>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Driver Name</label>
                    <input type="text" id="editDriverName" readonly style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:#f1f5f9;color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Performance Score (1.0 to 5.0)</label>
                    <input type="number" name="performance_score" id="editPerformanceScore" min="1" max="5" step="0.1" required style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Evaluation Status</label>
                    <select name="status" id="editStatus" style="width:100%;padding:0.6rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);">
                        <option value="active">Completed</option>
                        <option value="review">Pending Review</option>
                    </select>
                </div>
            </div>
            <div style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editReviewModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update Review</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.edit-review-btn');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');
    const score = btn.getAttribute('data-score');
    const status = btn.getAttribute('data-status');

    const modal = document.getElementById('editReviewModal');
    if (modal) {
        document.getElementById('editReviewForm').action = '/admin/performance/reviews/' + id;
        document.getElementById('editDriverName').value = name;
        document.getElementById('editPerformanceScore').value = score;
        document.getElementById('editStatus').value = status === 'active' ? 'active' : 'review';
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
});
</script>

@endsection
