@extends('admin.layouts.admin')

@section('title', 'TripWise — Training Schedule')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.training.index') }}">Training Management</a>
    <span>/</span>
    <span>Training Schedule</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Training Schedule</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Create, organize, and manage all driver training schedules.</p>
    </div>
    <button class="btn btn-primary" onclick="openModal('addTrainingModal')"><i class="fas fa-plus"></i> Add Training</button>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <h3>{{ $stats['upcoming'] }}</h3>
            <p>Upcoming Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-spinner"></i></div>
        <div class="card-info">
            <h3>{{ $stats['ongoing'] }}</h3>
            <p>Ongoing Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-check-circle"></i></div>
        <div class="card-info">
            <h3>{{ $stats['completed'] }}</h3>
            <p>Completed Trainings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-layer-group"></i></div>
        <div class="card-info">
            <h3>{{ $stats['total'] }}</h3>
            <p>Total Training Sessions</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="table-card" style="margin-bottom:1rem;">
    <form method="GET" action="{{ route('admin.training.schedule') }}" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;">
        <div style="flex:1;min-width:200px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search trainings..." style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
            <select name="category" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Categories</option>
                <option value="safety" {{ request('category') === 'safety' ? 'selected' : '' }}>Safety</option>
                <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Technical</option>
                <option value="soft_skills" {{ request('category') === 'soft_skills' ? 'selected' : '' }}>Soft Skills</option>
                <option value="compliance" {{ request('category') === 'compliance' ? 'selected' : '' }}>Compliance</option>
            </select>
        </div>
        <div style="min-width:180px;">
            <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
            <select name="status" style="width:100%;padding:0.6rem 1rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option value="">All Statuses</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Filter</button>
    </form>
</div>

<!-- Training Schedule Table -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-calendar-alt"></i> Training Schedule</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Training ID</th>
                    <th>Training Title</th>
                    <th>Category</th>
                    <th>Trainer</th>
                    <th>Venue</th>
                    <th>Schedule</th>
                    <th>Available Slots</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                    <tr>
                        <td>#TRN-{{ str_pad($training->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ $training->title }}</strong></td>
                        <td style="text-transform:capitalize;">{{ $training->category }}</td>
                        <td>{{ $training->instructor }}</td>
                        <td>{{ $training->venue ?? 'N/A' }}</td>
                        <td>{{ $training->start_datetime->format('M d, Y h:i A') }}</td>
                        <td>{{ $training->capacity }}</td>
                        <td>
                            <span class="item-badge {{ $training->status === 'upcoming' ? 'badge-info' : ($training->status === 'ongoing' ? 'badge-success' : ($training->status === 'completed' ? 'badge-success' : 'badge-danger')) }}">
                                {{ ucfirst($training->status) }}
                            </span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex;gap:0.35rem;justify-content:flex-end;">
                                <button type="button" class="btn btn-sm btn-secondary" title="View Training Details" style="color:#dc2626;border-color:#fca5a5;" onclick="if(window.openViewTrainingModal){ window.openViewTrainingModal({{ json_encode([
                                    'id' => '#TRN-' . str_pad($training->id, 5, '0', STR_PAD_LEFT),
                                    'title' => $training->title,
                                    'category' => ucfirst($training->category),
                                    'instructor' => $training->instructor,
                                    'venue' => $training->venue ?? 'Main Training Garage Center',
                                    'schedule' => $training->start_datetime ? $training->start_datetime->format('M d, Y h:i A') : 'TBD',
                                    'slots' => $training->capacity,
                                    'status' => ucfirst($training->status)
                                ]) }}); } else { const m = document.getElementById('viewTrainingModal'); if(m){ m.style.display='flex'; m.style.visibility='visible'; m.style.opacity='1'; } }"><i class="fas fa-eye"></i></button>

                                <button type="button" class="btn btn-sm btn-primary" title="Edit Training Session" style="background:#ef4444;border-color:#ef4444;" onclick="if(window.openEditTrainingModal){ window.openEditTrainingModal({{ json_encode([
                                    'id' => $training->id,
                                    'title' => $training->title,
                                    'category' => $training->category,
                                    'instructor' => $training->instructor,
                                    'venue' => $training->venue,
                                    'capacity' => $training->capacity,
                                    'status' => $training->status
                                ]) }}); } else { const m = document.getElementById('editTrainingModal'); if(m){ m.style.display='flex'; m.style.visibility='visible'; m.style.opacity='1'; } }"><i class="fas fa-edit"></i></button>

                                <form action="{{ route('admin.training.schedule.cancel', $training->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this training schedule for {{ $training->title }}?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary" style="background:#f1f5f9;border-color:#cbd5e1;color:#475569;" title="Cancel Session"><i class="fas fa-times"></i></button>
                                </form>

                                <form action="{{ route('admin.training.schedule.destroy', $training->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive/delete this training session record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" style="background:#dc2626;border-color:#dc2626;" title="Archive / Delete Record"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:2rem;">No trainings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">
        {{ $trainings->links() }}
    </div>
</div>

<!-- View Training Details Modal -->
<div id="viewTrainingModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:550px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:#fff7ed;border-top-left-radius:1rem;border-top-right-radius:1rem;">
            <h2 style="font-size:1.2rem;color:#c2410c;font-family:'Poppins',sans-serif;margin:0;font-weight:700;"><i class="fas fa-calendar-alt" style="margin-right:0.5rem;"></i> Training Session Details</h2>
            <button type="button" onclick="closeModal('viewTrainingModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding:1.5rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;background:#f8fafc;padding:1rem;border-radius:0.5rem;border:1px solid #e2e8f0;">
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Training ID</span>
                    <strong id="trnModalId" style="font-size:0.95rem;color:var(--primary);">#TRN-00001</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Category</span>
                    <span id="trnModalCategory" class="item-badge badge-info">Technical</span>
                </div>
                <div style="grid-column:span 2;">
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Training Title</span>
                    <strong id="trnModalTitle" style="font-size:1rem;color:#c2410c;">Title Here</strong>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Instructor / Trainer</span>
                    <span id="trnModalTrainer" style="font-size:0.9rem;font-weight:600;">Trainer Name</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Venue</span>
                    <span id="trnModalVenue" style="font-size:0.85rem;">Main Garage</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Schedule Date & Time</span>
                    <span id="trnModalSchedule" style="font-size:0.85rem;font-weight:600;">Nov 11, 2026</span>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;display:block;">Available Capacity</span>
                    <span id="trnModalSlots" style="font-size:0.9rem;font-weight:700;color:#059669;">30 Slots</span>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewTrainingModal')">Close</button>
        </div>
    </div>
</div>

<!-- Edit Training Modal -->
<div id="editTrainingModal" class="modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2200;align-items:center;justify-content:center;padding:2rem;">
    <div class="modal-container" style="background:var(--white);border-radius:1rem;width:100%;max-width:520px;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <form id="editTrainingForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header" style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <h2 style="font-size:1.25rem;color:var(--primary);font-family:'Poppins',sans-serif;margin:0;">Edit Training Schedule</h2>
                <button type="button" onclick="closeModal('editTrainingModal')" style="background:none;border:none;font-size:1.5rem;color:var(--text-muted);cursor:pointer;"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Training Title</label>
                    <input type="text" name="title" id="editTrnTitle" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Category</label>
                        <select name="category" id="editTrnCategory" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                            <option value="technical">Technical</option>
                            <option value="safety">Safety</option>
                            <option value="compliance">Compliance</option>
                            <option value="leadership">Leadership</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Trainer</label>
                        <input type="text" name="instructor" id="editTrnInstructor" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Venue</label>
                        <input type="text" name="venue" id="editTrnVenue" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                    <div>
                        <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Capacity Slots</label>
                        <input type="number" name="capacity" id="editTrnCapacity" min="1" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:0.85rem;font-weight:600;margin-bottom:0.4rem;">Status</label>
                    <select name="status" id="editTrnStatus" style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.9rem;">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editTrainingModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" style="background:#ef4444;border-color:#ef4444;"><i class="fas fa-check"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
window.openModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'flex';
        el.style.visibility = 'visible';
        el.style.opacity = '1';
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(id) {
    const el = document.getElementById(id);
    if (el) {
        el.style.display = 'none';
        document.body.style.overflow = '';
    }
};

window.openViewTrainingModal = function(data) {
    document.getElementById('trnModalId').innerText = data.id;
    document.getElementById('trnModalTitle').innerText = data.title;
    document.getElementById('trnModalCategory').innerText = data.category;
    document.getElementById('trnModalTrainer').innerText = data.instructor;
    document.getElementById('trnModalVenue').innerText = data.venue;
    document.getElementById('trnModalSchedule').innerText = data.schedule;
    document.getElementById('trnModalSlots').innerText = data.slots + ' Available Slots';
    window.openModal('viewTrainingModal');
};

window.openEditTrainingModal = function(data) {
    document.getElementById('editTrainingForm').action = '/admin/training/schedule/' + data.id;
    document.getElementById('editTrnTitle').value = data.title;
    document.getElementById('editTrnCategory').value = data.category;
    document.getElementById('editTrnInstructor').value = data.instructor;
    document.getElementById('editTrnVenue').value = data.venue || '';
    document.getElementById('editTrnCapacity').value = data.capacity;
    document.getElementById('editTrnStatus').value = data.status;
    window.openModal('editTrainingModal');
};
</script>

<!-- Calendar View -->
<div class="table-card" style="margin-top:1.5rem;">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-calendar"></i> Training Calendar</h3>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Week</th>
                    <th>Day</th>
                    <th>Training</th>
                    <th>Time</th>
                    <th>Instructor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $calendarData = [
                        ['month'=>'July 2026','week'=>'Week 3','day'=>'15','training'=>'Defensive Driving Workshop','time'=>'9:00 AM','instructor'=>'Internal SecOps','status'=>'upcoming'],
                        ['month'=>'July 2026','week'=>'Week 4','day'=>'22','training'=>'First Aid Certification','time'=>'1:00 PM','instructor'=>'Red Cross','status'=>'upcoming'],
                        ['month'=>'August 2026','week'=>'Week 1','day'=>'05','training'=>'Eco-Driving Techniques','time'=>'10:00 AM','instructor'=>'Fleet Mgmt','status'=>'upcoming'],
                    ];
                @endphp
                @foreach($calendarData as $event)
                <tr>
                    <td>{{ $event['month'] }}</td>
                    <td>{{ $event['week'] }}</td>
                    <td><strong>{{ $event['day'] }}</strong></td>
                    <td><strong>{{ $event['training'] }}</strong></td>
                    <td>{{ $event['time'] }}</td>
                    <td>{{ $event['instructor'] }}</td>
                    <td>
                        <span class="item-badge badge-info">{{ ucfirst($event['status']) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Charts -->
<div class="charts-grid" style="margin-top:1.5rem;">
    <div class="chart-card">
        <h3><i class="fas fa-chart-bar"></i> Training Schedule</h3>
        <div class="chart-wrapper">
            <canvas id="trainingScheduleChart"></canvas>
        </div>
    </div>
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Training Status Distribution</h3>
        <div class="chart-wrapper">
            <canvas id="trainingStatusChart"></canvas>
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

    new Chart(document.getElementById('trainingScheduleChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($scheduleData->pluck('month_num')->toArray()) !!},
            datasets: [{
                label: 'Trainings Scheduled',
                data: {!! json_encode($scheduleData->pluck('total')->toArray()) !!},
                backgroundColor: '#10b981',
                borderRadius: 8
            }]
        },
        options: { ...chartDefaults, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('trainingStatusChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($statusData->pluck('status')->toArray()) !!},
            datasets: [{ data: {!! json_encode($statusData->pluck('total')->toArray()) !!}, backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#6366f1'] }]
        },
        options: { ...chartDefaults, plugins: { legend: { position: 'bottom', labels: { font: { family: "'Poppins', sans-serif" } } } } }
    });
});
</script>
@endsection
