<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TalentSuite — @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#F44336',
                            dark: '#D32F2F',
                            light: '#EF5350',
                            soft: '#fff5f5',
                        },
                        forest: {
                            DEFAULT: '#1c1c1e',
                            dark: '#111112',
                            light: '#2c2c2e',
                        },
                        cream: {
                            DEFAULT: '#faf9f6',
                            dark: '#f1efe9',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    keyframes: {
                        fadeIn: { from: { opacity: '0', transform: 'translateY(8px)' }, to: { opacity: '1', transform: 'translateY(0)' } }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease forwards',
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f1efe9; }
        ::-webkit-scrollbar-thumb { background: #F44336; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #D32F2F; }

        .nav-btn.active {
            background-color: #fff5f5;
            color: #F44336;
            font-weight: 600;
        }
        .dark .nav-btn.active {
            background-color: rgba(244,67,54,0.12);
            color: #EF5350;
        }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.25s ease forwards; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- Prevent dark-mode flash -->
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @stack('styles')
</head>
<body class="bg-cream dark:bg-forest-dark text-slate-800 dark:text-cream antialiased min-h-screen flex overflow-x-hidden transition-colors duration-200">

    <!-- ===================== MOBILE BACKDROP ===================== -->
    <div id="sidebar-backdrop"
         class="fixed inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm z-30 hidden lg:hidden"
         onclick="toggleSidebar()"></div>

    <!-- ===================== SIDEBAR ===================== -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-forest border-r border-slate-200 dark:border-forest-light z-40 transform -translate-x-full lg:translate-x-0 lg:static lg:flex lg:flex-col transition-transform duration-300 h-screen flex flex-col shadow-sm">

        <!-- Logo -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-200 dark:border-forest-light shrink-0">
            <a href="#" class="flex items-center gap-2.5 font-outfit font-bold text-xl tracking-tight text-brand dark:text-brand-light">
                <img src="{{ asset('tripwise_logo.png') }}" alt="Tripwise Logo" class="w-8 h-8 object-contain">
                <span>Tripwise</span>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-zinc-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6">
            <div>
                <span class="px-3 text-[10px] font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-2">Main</span>
                <ul class="space-y-1">
                    <li>
                        <button onclick="switchTab('dashboard-overview')" id="tab-btn-dashboard-overview"
                                class="nav-btn active w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                            </svg>
                            <span>Overview</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div>
                <span class="px-3 text-[10px] font-semibold text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-2">HR Modules</span>
                <ul class="space-y-1">
                    <!-- Performance -->
                    <li>
                        <button onclick="switchTab('performance')" id="tab-btn-performance"
                                class="nav-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Performance Management</span>
                            </div>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 uppercase tracking-wide">Live</span>
                        </button>
                    </li>
                    <!-- Competency -->
                    <li>
                        <button onclick="switchTab('competency')" id="tab-btn-competency"
                                class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span>Competency Management</span>
                        </button>
                    </li>
                    <!-- Learning -->
                    <li>
                        <button onclick="switchTab('learning')" id="tab-btn-learning"
                                class="nav-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <span>Learning Management</span>
                            </div>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-brand-soft dark:bg-brand/10 text-brand dark:text-brand-light uppercase">LMS</span>
                        </button>
                    </li>
                    <!-- Training -->
                    <li>
                        <button onclick="switchTab('training')" id="tab-btn-training"
                                class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Training Management</span>
                        </button>
                    </li>
                    <!-- Succession -->
                    <li>
                        <button onclick="switchTab('succession')" id="tab-btn-succession"
                                class="nav-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Succession Planning</span>
                        </button>
                    </li>
                    <!-- Recognition -->
                    <li>
                        <button onclick="switchTab('recognition')" id="tab-btn-recognition"
                                class="nav-btn w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 dark:text-zinc-400 dark:hover:text-zinc-100 dark:hover:bg-zinc-800/60 transition-all duration-150">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span>Social Recognition</span>
                            </div>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/30 text-rose-500 uppercase">Social</span>
                        </button>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar Footer: User Profile -->
        <div class="p-3 border-t border-slate-200 dark:border-forest-light shrink-0">
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-forest-light/40 transition-all">
                <img class="w-9 h-9 rounded-full border-2 border-brand-soft object-cover"
                     src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=80&h=80&q=80" alt="Sarah Connor">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200 truncate">Sarah Connor</p>
                    <p class="text-xs text-slate-400 dark:text-zinc-500 truncate">HR Director</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- ===================== MAIN CONTENT AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto bg-cream dark:bg-forest-dark">

        <!-- Top Header Bar -->
        <header class="h-16 bg-white dark:bg-forest border-b border-slate-200 dark:border-forest-light flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <!-- Mobile Hamburger -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-slate-500 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <!-- Breadcrumb -->
                <div class="hidden sm:flex items-center gap-2 text-sm">
                    <span class="text-slate-400 dark:text-zinc-500">Workspace</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span id="breadcrumb-active" class="font-semibold text-slate-700 dark:text-zinc-200">Overview Dashboard</span>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <!-- Search -->
                <div class="relative hidden md:block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" placeholder="Search talent..."
                           class="w-60 pl-9 pr-4 py-2 rounded-lg border border-slate-200 dark:border-forest-light bg-slate-50 dark:bg-forest-dark text-sm focus:outline-none focus:ring-2 focus:ring-brand dark:focus:ring-brand-light placeholder-slate-400 dark:placeholder-zinc-500 transition-all">
                </div>
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" id="theme-toggle-btn"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors" title="Toggle Theme">
                    <svg id="icon-sun" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                    </svg>
                    <svg id="icon-moon" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
                <!-- Notifications -->
                <button class="relative w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 dark:text-zinc-400 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-brand rounded-full ring-2 ring-white dark:ring-forest"></span>
                </button>
            </div>
        </header>

        <!-- Page Content Slot -->
        <main class="flex-1 p-6 md:p-8 max-w-7xl w-full mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- ===================== SHARED SCRIPTS ===================== -->
    <script>
        // --- Tab Switching ---
        const tabLabels = {
            'dashboard-overview': 'Overview Dashboard',
            'performance':        'Performance Management',
            'competency':         'Competency Management',
            'learning':           'Learning Management',
            'training':           'Training Management',
            'succession':         'Succession Planning',
            'recognition':        'Social Recognition',
        };

        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(el => el.classList.remove('active'));

            const target = document.getElementById('tab-content-' + tabId);
            if (target) target.classList.add('active');

            const btn = document.getElementById('tab-btn-' + tabId);
            if (btn) btn.classList.add('active');

            const bc = document.getElementById('breadcrumb-active');
            if (bc) bc.textContent = tabLabels[tabId] || tabId;
        }

        // --- Mobile Sidebar ---
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        // --- Dark Mode ---
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons();
        }

        function updateThemeIcons() {
            const dark = document.documentElement.classList.contains('dark');
            document.getElementById('icon-sun').classList.toggle('hidden', !dark);
            document.getElementById('icon-moon').classList.toggle('hidden', dark);
        }

        // Init icons on load
        document.addEventListener('DOMContentLoaded', () => {
            updateThemeIcons();
            // Show first tab
            const firstTab = document.querySelector('.tab-content');
            if (firstTab) firstTab.classList.add('active');
        });

        // --- Social Recognition clap button ---
        function incrementClap(btn) {
            const counter = btn.querySelector('.clap-count');
            if (counter) counter.textContent = parseInt(counter.textContent) + 1;
        }
    </script>

    @stack('scripts')
</body>
</html>