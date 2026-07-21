@extends('admin.layouts.admin')

@section('title', 'TripWise — Notification Settings')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <a href="{{ route('admin.notifications.index') }}">Notifications</a>
    <span>/</span>
    <span>Notification Settings</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Notification Settings</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Configure notification preferences for administrators and drivers. Manage channels, frequencies, and categories.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="document.getElementById('resetSettingsForm').submit();"><i class="fas fa-undo"></i> Reset to Default</button>
        <button class="btn btn-primary" onclick="document.getElementById('settingsForm').submit();"><i class="fas fa-save"></i> Save Settings</button>
    </div>
</div>

<!-- Dashboard Stats Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="card-icon blue"><i class="fas fa-plug"></i></div>
        <div class="card-info">
            <h3>{{ $stats['active_channels'] }}</h3>
            <p>Active Notification Channels</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon green"><i class="fas fa-toggle-off"></i></div>
        <div class="card-info">
            <h3>{{ $stats['disabled_notifications'] }}</h3>
            <p>Disabled Notifications</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon orange"><i class="fas fa-clock"></i></div>
        <div class="card-info">
            <h3>{{ $stats['reminder_settings'] }}</h3>
            <p>Reminder Settings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon purple"><i class="fas fa-paper-plane"></i></div>
        <div class="card-info">
            <h3>{{ $stats['delivery_preferences'] }}</h3>
            <p>Delivery Preferences</p>
        </div>
    </div>
</div>

<!-- Settings Form -->
<div class="table-card">
    <h3 style="margin:0 0 1rem;"><i class="fas fa-cog"></i> Notification Preferences</h3>
    <form id="settingsForm" method="POST" action="{{ route('admin.notifications.settings.store') }}">
        @csrf
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>In-App</th>
                        <th>Email</th>
                        <th>SMS</th>
                        <th>Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['training', 'performance', 'system', 'announcement'] as $category)
                    <tr>
                        <td><strong>{{ ucfirst($category) }}</strong></td>
                        @foreach(['in_app', 'email', 'sms'] as $type)
                            @php
                                $setting = $settings->first(fn($s) => $s->category === $category && $s->type === $type);
                                $enabled = $setting?->enabled ?? true;
                                $frequency = $setting?->frequency ?? 'immediate';
                            @endphp
                            <td style="text-align:center;">
                                <input type="checkbox" name="settings[{{ $category }}][{{ $type }}][enabled]" value="1" {{ $enabled ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                            </td>
                        @endforeach
                        <td>
                            <select name="settings[{{ $category }}][frequency]" style="padding:0.4rem 0.75rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                                <option value="immediate" {{ ($settings->first(fn($s) => $s->category === $category)?->frequency ?? 'immediate') === 'immediate' ? 'selected' : '' }}>Immediate</option>
                                <option value="daily" {{ ($settings->first(fn($s) => $s->category === $category)?->frequency ?? '') === 'daily' ? 'selected' : '' }}>Daily Digest</option>
                                <option value="weekly" {{ ($settings->first(fn($s) => $s->category === $category)?->frequency ?? '') === 'weekly' ? 'selected' : '' }}>Weekly Digest</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1rem;display:flex;gap:0.75rem;justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="showToast('Test notification sent successfully!');"><i class="fas fa-paper-plane"></i> Test Notification</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Settings</button>
        </div>
    </form>
    <form id="resetSettingsForm" method="POST" action="{{ route('admin.notifications.settings.reset') }}" style="display:none;">
        @csrf
        @method('POST')
    </form>
</div>

@endsection

@push('scripts')
<script>
function exportReport(format) {
    showToast('Exporting settings as ' + format.toUpperCase() + '...');
}
</script>
@endpush
