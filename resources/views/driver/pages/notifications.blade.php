@extends('driver.layouts.driver')

@section('title', 'TripWise — Notifications')

@section('content')
    <div class="breadcrumb">
        <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
        <span>/</span>
        <span>Notifications</span>
    </div>

    <div class="page-header">
        <div>
            <h1>Notifications</h1>
            <p>Stay updated with your latest alerts and announcements.</p>
        </div>
    </div>

    <div class="table-card">
        <h3><i class="fas fa-bell"></i> Recent Notifications</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td><strong>{{ $notification->title ?? 'Notification' }}</strong></td>
                            <td>{{ $notification->message ?? '' }}</td>
                            <td>{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->format('M dd, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                No notifications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
