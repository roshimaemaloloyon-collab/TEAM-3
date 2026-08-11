<aside class="sidebar" id="sidebar">
    <div class="logo-area">
        <img src="{{ asset('tripwise_logo.png') }}" alt="Tripwise">
        <span class="logo-text">Tripwise</span>
    </div>

    <nav class="nav-menu">
        <a href="{{ route('driver.dashboard') }}" class="nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i><span class="nav-text">Dashboard</span>
        </a>

        <div class="nav-section-header">My Work</div>
        <a href="{{ route('driver.performance') }}" class="nav-item {{ request()->routeIs('driver.performance') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i><span class="nav-text">My Performance</span>
        </a>
        <a href="{{ route('driver.competencies') }}" class="nav-item {{ request()->routeIs('driver.competencies') ? 'active' : '' }}">
            <i class="fas fa-brain"></i><span class="nav-text">My Competencies</span>
        </a>
        <a href="{{ route('driver.learning') }}" class="nav-item {{ request()->routeIs('driver.learning') ? 'active' : '' }}">
            <i class="fas fa-book-open"></i><span class="nav-text">Learning Modules</span>
        </a>
        <a href="{{ route('driver.trainings') }}" class="nav-item {{ request()->routeIs('driver.trainings') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i><span class="nav-text">My Trainings</span>
        </a>

        <div class="nav-section-header">Growth & Career</div>
        <a href="{{ route('driver.career') }}" class="nav-item {{ request()->routeIs('driver.career') ? 'active' : '' }}">
            <i class="fas fa-rocket"></i><span class="nav-text">Career Growth</span>
        </a>
        <a href="{{ route('driver.recognition') }}" class="nav-item {{ request()->routeIs('driver.recognition') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i><span class="nav-text">Recognition & Achievements</span>
        </a>

        <div class="nav-section-header">Community</div>
        <a href="{{ route('driver.evaluations') }}" class="nav-item {{ request()->routeIs('driver.evaluations') ? 'active' : '' }}">
            <i class="fas fa-users"></i><span class="nav-text">Peer-to-Peer Evaluation</span>
        </a>

        <div class="nav-section-header">Account</div>
        <a href="{{ route('driver.reports') }}" class="nav-item {{ request()->routeIs('driver.reports') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i><span class="nav-text">Reports</span>
        </a>
        <a href="{{ route('driver.notifications') }}" class="nav-item {{ request()->routeIs('driver.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i><span class="nav-text">Notifications</span>
        </a>
        <a href="{{ route('driver.settings') }}" class="nav-item {{ request()->routeIs('driver.settings') ? 'active' : '' }}">
            <i class="fas fa-cog"></i><span class="nav-text">Settings</span>
        </a>
        <a href="{{ route('logout') }}" class="nav-item" style="color: #ef4444;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </nav>

    <div class="user-area">
        <a href="{{ url('driver/profile') }}" style="display:flex;align-items:center;gap:0.75rem;text-decoration:none;color:inherit;">
            <img src="{{ $driver->photo ?: asset('drivers/photo/' . ($driver->id ?? 1)) }}" alt="Driver" class="user-avatar">
            <div class="user-info">
                <div class="user-name">{{ $driver->full_name ?? 'Driver' }}</div>
                <div class="user-role">{{ $driver->vehicle_type ?? 'Professional Driver' }}</div>
            </div>
        </a>
    </div>
</aside>
