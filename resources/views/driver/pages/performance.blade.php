@extends('driver.layouts.driver')

@section('title', 'TripWise — My Performance')

@section('content')
        <div class="breadcrumb">
            <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
            <span>/</span>
            <span>My Performance</span>
        </div>

        <div class="page-header">
            <div>
                <h1>My Performance</h1>
                <p>Track your performance metrics, KPIs, and ratings over time.</p>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="card-icon blue"><i class="fas fa-star"></i></div>
                <div class="card-info">
                    <h3>{{ number_format($myOverallScore ?? 0, 1) }}/5</h3>
                    <p>Overall Score</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon green"><i class="fas fa-smile"></i></div>
                <div class="card-info">
                    <h3>{{ number_format($kpiStats['customer_rating'] ?? 0, 1) }}</h3>
                    <p>Customer Rating</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon orange"><i class="fas fa-shield-alt"></i></div>
                <div class="card-info">
                    <h3>{{ number_format($kpiStats['safety_score'] ?? 0, 1) }}</h3>
                    <p>Safety Score</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="card-icon purple"><i class="fas fa-calendar-check"></i></div>
                <div class="card-info">
                    <h3>{{ number_format($kpiStats['attendance_rate'] ?? 0, 1) }}%</h3>
                    <p>Attendance Rate</p>
                </div>
            </div>
        </div>

        <div class="table-card">
            <h3><i class="fas fa-list"></i> Recent Performance Records</h3>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer Rating</th>
                            <th>Peer Evaluation</th>
                            <th>Attendance</th>
                            <th>Trip Completion</th>
                            <th>Safety</th>
                            <th>Overall</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($performances as $performance)
                            <tr>
                                <td>{{ $performance->recorded_at ? \Carbon\Carbon::parse($performance->recorded_at)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ number_format($performance->customer_rating ?? 0, 1) }}</td>
                                <td>{{ number_format($performance->peer_evaluation_score ?? 0, 1) }}</td>
                                <td>{{ number_format($performance->attendance_rate ?? 0, 1) }}%</td>
                                <td>{{ number_format($performance->trip_completion_rate ?? 0, 1) }}%</td>
                                <td>{{ number_format($performance->safety_score ?? 0, 1) }}</td>
                                <td><strong>{{ number_format($performance->overall_score ?? 0, 1) }}</strong></td>
                                <td>
                                    <span class="status-badge {{ $performance->performance_status === 'excellent' ? 'status-active' : ($performance->performance_status === 'good' ? 'status-review' : 'status-pending') }}">
                                        {{ ucfirst(str_replace('_', ' ', $performance->performance_status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                    No performance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:1rem;">
                {{ $performances->links() }}
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-card">
                <h3><i class="fas fa-chart-line"></i> Performance Trend</h3>
                <div class="chart-wrapper">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-chart-pie"></i> KPI Breakdown</h3>
                <div class="chart-wrapper">
                    <canvas id="kpiChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartDefaults = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { font: { family: "'Poppins', sans-serif" } } } }
            };

            new Chart(document.getElementById('performanceChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($performances->map(fn($p) => \Carbon\Carbon::parse($p->recorded_at)->format('M d, Y'))->toArray()) !!},
                    datasets: [{
                        label: 'Overall Score',
                        data: {!! json_encode($performances->map(fn($p) => $p->overall_score)->toArray()) !!},
                        borderColor: '#F44336',
                        backgroundColor: 'rgba(244, 67, 54, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#F44336'
                    }]
                },
                options: { ...chartDefaults, plugins: { legend: { display: false } } }
            });

            new Chart(document.getElementById('kpiChart'), {
                type: 'bar',
                data: {
                    labels: ['Safety', 'Attendance', 'Trip Completion', 'Customer Rating'],
                    datasets: [{
                        label: 'Score',
                        data: [
                            {{ $kpiStats['safety_score'] ?? 0 }},
                            {{ $kpiStats['attendance_rate'] ?? 0 }},
                            {{ $kpiStats['trip_completion_rate'] ?? 0 }},
                            {{ $kpiStats['customer_rating'] ?? 0 }}
                        ],
                        backgroundColor: ['#F44336', '#10b981', '#3b82f6', '#f59e0b'],
                        borderRadius: 8
                    }]
                },
                options: { ...chartDefaults, scales: { y: { beginAtZero: true, max: 100 } } }
            });
        });
    </script>
@endsection