@extends('driver.layouts.driver')

@section('title', 'TripWise — Learning Modules')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Learning Modules</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Learning Modules</h1>
            <p>Access your assigned learning materials and track progress.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-book-open"></i> Available Modules</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Module</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Assigned Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modules as $module)
                        <tr>
                            <td><strong>{{ $module->title ?? $module->name }}</strong></td>
                            <td>{{ $module->category ?? 'General' }}</td>
                            <td>
                            @php
                                $start = $module->start_datetime;
                                $end = $module->end_datetime;
                                if ($start && $end) {
                                    echo $start->diffInHours($end) . ' hrs';
                                } else {
                                    echo 'N/A';
                                }
                            @endphp
                        </td>
                            <td>
                                <span class="status-badge {{ $module->status === 'completed' ? 'status-active' : ($module->status === 'ongoing' ? 'status-review' : 'status-pending') }}">
                                    {{ ucfirst($module->status ?? 'Assigned') }}
                                </span>
                            </td>
                            <td>{{ $module->created_at ? \Carbon\Carbon::parse($module->created_at)->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No learning modules assigned yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $modules->links() }}
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-certificate"></i> My Certificates</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Certificate</th>
                        <th>Issued Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certificates as $certificate)
                        <tr>
                            <td><strong>{{ $certificate->name ?? 'Certificate' }}</strong></td>
                            <td>{{ $certificate->created_at ? \Carbon\Carbon::parse($certificate->created_at)->format('M dd, Y') : 'N/A' }}</td>
                            <td>
                                <span class="status-badge {{ $certificate->status === 'issued' ? 'status-active' : 'status-pending' }}">
                                    {{ ucfirst($certificate->status ?? 'Pending') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No certificates earned yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $certificates->links() }}
        </div>
    </div>
@endsection
