@extends('layouts.app')

@section('TripWise', 'HR Dashboard')

@section('content')

{{-- ============================================================
     1. OVERVIEW DASHBOARD TAB
     ============================================================ --}}
<div id="tab-content-dashboard-overview" class="tab-content space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Welcome back, Driver 👋</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Here's what's happening across your HR modules today.</p>
        </div>
        <button onclick="switchTab('recognition')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Give Recognition
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Stat 1 -->
        <div onclick="switchTab('performance')"
             class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-5 hover:shadow-md transition-all cursor-pointer group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500 dark:text-zinc-400">Avg. Rating</span>
                <span class="p-2 bg-emerald-50 dark:bg-emerald-950/30 rounded-lg text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <h3 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">4.2 <span class="text-xs font-normal text-slate-400">/ 5.0</span></h3>
            <p class="text-xs text-emerald-600 font-semibold mt-1">↑ 4% this quarter</p>
        </div>
        <!-- Stat 2 -->
        <div onclick="switchTab('competency')"
             class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-5 hover:shadow-md transition-all cursor-pointer group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500 dark:text-zinc-400">Competency Fit</span>
                <span class="p-2 bg-blue-50 dark:bg-blue-950/30 rounded-lg text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547"/>
                    </svg>
                </span>
            </div>
            <h3 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">87.5%</h3>
            <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-full mt-2 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="width:87.5%"></div>
            </div>
        </div>
        <!-- Stat 3 -->
        <div onclick="switchTab('learning')"
             class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-5 hover:shadow-md transition-all cursor-pointer group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500 dark:text-zinc-400">LMS Completion</span>
                <span class="p-2 bg-amber-50 dark:bg-amber-950/30 rounded-lg text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                    </svg>
                </span>
            </div>
            <h3 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">78%</h3>
            <p class="text-xs text-amber-600 font-semibold mt-1">2 certifications pending</p>
        </div>
        <!-- Stat 4 -->
        <div onclick="switchTab('succession')"
             class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-5 hover:shadow-md transition-all cursor-pointer group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500 dark:text-zinc-400">Succession Depth</span>
                <span class="p-2 bg-rose-50 dark:bg-rose-950/30 rounded-lg text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7"/>
                    </svg>
                </span>
            </div>
            <h3 class="mt-4 text-2xl font-bold text-slate-900 dark:text-white">14 <span class="text-xs font-normal text-slate-400">Talents</span></h3>
            <p class="text-xs text-slate-500 mt-1">For 4 critical executive roles</p>
        </div>
    </div>

    <!-- Lower Grid: Recognition Feed + Upcoming Trainings -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recognition Feed -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 flex flex-col h-96">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800 mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">Social Recognition Feed</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Peer shout-outs and achievements</p>
                </div>
                <button onclick="switchTab('recognition')" class="text-xs font-semibold text-brand hover:underline">View Wall →</button>
            </div>
            <div class="flex-1 overflow-y-auto space-y-4">
                <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-zinc-900 shrink-0"
                         src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&h=80&q=80" alt="Emily">
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Emily Watson <span class="font-normal text-slate-400">recognized</span> David Miller</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 italic">"David worked late Friday to deploy critical fixes for the payment system. Pure customer focus!"</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">🏆 Customer Focus</span>
                            <span class="text-[10px] text-slate-400">2 hours ago</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-zinc-800/40 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-zinc-900 shrink-0"
                         src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=80&h=80&q=80" alt="Marcus">
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-zinc-200">Marcus Vance <span class="font-normal text-slate-400">recognized</span> Sarah Connor</p>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 mt-1 italic">"Awesome planning session for Succession Pools. The visual matrices were exactly what the board wanted!"</p>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">💡 Collaboration</span>
                            <span class="text-[10px] text-slate-400">Yesterday</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Trainings -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 flex flex-col h-96">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-zinc-800 mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg">Upcoming Trainings</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Scheduled sessions</p>
                </div>
                <button onclick="switchTab('training')" class="text-xs font-semibold text-brand hover:underline">View All →</button>
            </div>
            <div class="flex-1 overflow-y-auto space-y-4">
                <div class="p-3 bg-blue-50/40 dark:bg-blue-950/15 rounded-xl border border-blue-100/50 dark:border-blue-900/20">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wide">Technology</span>
                        <span class="text-[10px] text-slate-400">July 15, 2026</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mt-1.5">Laravel 11 Advanced Patterns</h4>
                    <p class="text-xs text-slate-500 mt-1">Instructor: Taylor Otwell · 12 registered</p>
                </div>
                <div class="p-3 bg-amber-50/40 dark:bg-amber-950/15 rounded-xl border border-amber-100/50 dark:border-amber-900/20">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wide">Compliance</span>
                        <span class="text-[10px] text-slate-400">July 22, 2026</span>
                    </div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mt-1.5">Cyber Security & Phishing Awareness</h4>
                    <p class="text-xs text-slate-500 mt-1">Internal SecOps · 45 registered</p>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     2. PERFORMANCE MANAGEMENT
     ============================================================ --}}
<div id="tab-content-performance" class="tab-content space-y-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Performance Management</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Track KPIs, OKRs, and review cycles across the organisation.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- OKR Progress -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">Q2 Objectives & Key Results</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">Scale engineering team — hire 3 developers</span>
                        <span class="text-blue-600 dark:text-blue-400">66%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width:66%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">Implement succession readiness reporting</span>
                        <span class="text-emerald-600 dark:text-emerald-400">100%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width:100%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">Improve eNPS score to +40</span>
                        <span class="text-rose-600 dark:text-rose-400">30%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-rose-500 h-full rounded-full" style="width:30%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Card -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 flex flex-col justify-between">
            <h3 class="font-bold text-slate-900 dark:text-white">Rating Breakdown</h3>
            <div class="flex justify-center my-6">
                <div class="w-32 h-32 rounded-full border-8 border-blue-100 dark:border-blue-950 flex flex-col items-center justify-center">
                    <span class="text-3xl font-extrabold text-blue-600 dark:text-blue-400">4.2</span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold mt-1">Excellent</span>
                </div>
            </div>
            <ul class="text-xs space-y-2">
                <li class="flex justify-between text-slate-500 dark:text-zinc-400 border-b border-slate-100 dark:border-zinc-800 pb-2">
                    <span>Target Goal Fit</span><span class="font-semibold text-slate-800 dark:text-zinc-200">85%</span>
                </li>
                <li class="flex justify-between text-slate-500 dark:text-zinc-400">
                    <span>Self Evaluation</span><span class="font-semibold text-emerald-600">Submitted</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Review Cycles Table -->
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 overflow-hidden">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-5">Evaluation Cycles</h3>
        <div class="overflow-x-auto -mx-6">
            <table class="w-full text-left text-sm min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-zinc-800/40 text-slate-500 dark:text-zinc-400 border-y border-slate-200 dark:border-zinc-800">
                        <th class="py-3 px-6 font-semibold">Cycle</th>
                        <th class="py-3 px-6 font-semibold">Due Date</th>
                        <th class="py-3 px-6 font-semibold">Reviewer</th>
                        <th class="py-3 px-6 font-semibold">Status</th>
                        <th class="py-3 px-6 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    <tr>
                        <td class="py-4 px-6 font-medium text-slate-900 dark:text-white">Q2 Mid-Year Self Review</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-zinc-400">July 31, 2026</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-zinc-400">Self Evaluation</td>
                        <td class="py-4 px-6"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">Pending</span></td>
                        <td class="py-4 px-6 text-right"><button class="text-xs font-bold text-brand hover:underline">Start Review</button></td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 font-medium text-slate-900 dark:text-white">Manager OKR Checkpoint</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-zinc-400">Aug 15, 2026</td>
                        <td class="py-4 px-6 text-slate-500 dark:text-zinc-400">Sarah Connor</td>
                        <td class="py-4 px-6"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600">Scheduled</span></td>
                        <td class="py-4 px-6 text-right"><span class="text-xs text-slate-400">Locked</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- ============================================================
     3. COMPETENCY MANAGEMENT
     ============================================================ --}}
<div id="tab-content-competency" class="tab-content space-y-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Competency Management</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Review professional skills, competency mappings, and gap analyses.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Skill Proficiency -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-6">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">Skill Proficiency Index</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">System Architecture & Design</span>
                        <span class="text-xs text-slate-500">Lvl 4 / 5</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width:80%"></div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Gap: 1 Level deficit — coaching recommended</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">PHP & Laravel Orchestration</span>
                        <span class="text-xs text-slate-500">Lvl 5 / 5</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width:100%"></div>
                    </div>
                    <p class="text-[11px] text-emerald-600 mt-1">✓ Perfect Fit — Fully Competent</p>
                </div>
                <div>
                    <div class="flex justify-between text-sm font-medium mb-1.5">
                        <span class="text-slate-700 dark:text-zinc-300">DevOps & Cloud (Docker/K8s)</span>
                        <span class="text-xs text-slate-500">Lvl 3 / 4</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width:75%"></div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Gap: 1 Level deficit — training scheduled</p>
                </div>
            </div>
        </div>

        <!-- Radar Map -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 flex flex-col">
            <h3 class="font-bold text-slate-900 dark:text-white text-base mb-4">Competency Radar Map</h3>
            <div class="flex justify-center items-center flex-1 py-4">
                <svg class="w-40 h-40 text-blue-500 dark:text-blue-400" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-dasharray="2 2" stroke-width="0.5" opacity="0.3"/>
                    <circle cx="50" cy="50" r="30" stroke="currentColor" stroke-dasharray="2 2" stroke-width="0.5" opacity="0.3"/>
                    <circle cx="50" cy="50" r="20" stroke="currentColor" stroke-dasharray="2 2" stroke-width="0.5" opacity="0.3"/>
                    <line x1="50" y1="50" x2="50" y2="10" stroke="currentColor" stroke-width="0.5" opacity="0.3"/>
                    <line x1="50" y1="50" x2="90" y2="50" stroke="currentColor" stroke-width="0.5" opacity="0.3"/>
                    <line x1="50" y1="50" x2="50" y2="90" stroke="currentColor" stroke-width="0.5" opacity="0.3"/>
                    <line x1="50" y1="50" x2="10" y2="50" stroke="currentColor" stroke-width="0.5" opacity="0.3"/>
                    <polygon points="50,22 80,50 50,78 30,50" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="50" cy="22" r="2.5" fill="currentColor"/>
                    <circle cx="80" cy="50" r="2.5" fill="currentColor"/>
                    <circle cx="50" cy="78" r="2.5" fill="currentColor"/>
                    <circle cx="30" cy="50" r="2.5" fill="currentColor"/>
                </svg>
            </div>
            <p class="text-center text-xs text-slate-400">Matches 4 of 5 critical role requirements</p>
        </div>
    </div>
</div>


{{-- ============================================================
     4. LEARNING MANAGEMENT (LMS)
     ============================================================ --}}
<div id="tab-content-learning" class="tab-content space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Learning Management</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Expand skills with structured training courses and certifications.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 rounded-lg text-sm font-semibold text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
            Explore Course Catalog
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Course 1 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl overflow-hidden flex flex-col hover:shadow-md transition-all group">
            <div class="p-6 flex-1">
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 mb-4">Leadership</span>
                <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-brand transition-colors">Leadership Essentials 101</h4>
                <p class="text-xs text-slate-500 mt-2">Master delegation, feedback loops, and conflict mitigation.</p>
                <div class="mt-5">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 mb-1.5"><span>Progress</span><span>80%</span></div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width:80%"></div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 border-t border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                <span class="text-xs text-slate-400">Lesson 8 of 10</span>
                <button class="text-xs font-bold text-brand hover:underline">Resume →</button>
            </div>
        </div>
        <!-- Course 2 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl overflow-hidden flex flex-col hover:shadow-md transition-all group">
            <div class="p-6 flex-1">
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 mb-4">Technology</span>
                <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-brand transition-colors">Advanced Laravel & Livewire</h4>
                <p class="text-xs text-slate-500 mt-2">Deep dive into query optimization, caching, and reactive components.</p>
                <div class="mt-5">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 mb-1.5"><span>Progress</span><span>45%</span></div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width:45%"></div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 border-t border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                <span class="text-xs text-slate-400">Lesson 4 of 12</span>
                <button class="text-xs font-bold text-brand hover:underline">Resume →</button>
            </div>
        </div>
        <!-- Course 3 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl overflow-hidden flex flex-col hover:shadow-md transition-all group">
            <div class="p-6 flex-1">
                <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 mb-4">Compliance</span>
                <h4 class="font-bold text-slate-900 dark:text-white group-hover:text-brand transition-colors">GDPR & Data Protection 2026</h4>
                <p class="text-xs text-slate-500 mt-2">Compliance policies for handling customer data and encryption.</p>
                <div class="mt-5">
                    <div class="flex justify-between text-xs font-semibold text-slate-500 mb-1.5"><span>Progress</span><span class="text-emerald-600">100%</span></div>
                    <div class="w-full bg-slate-100 dark:bg-zinc-800 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-500 h-full rounded-full" style="width:100%"></div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-zinc-800/40 border-t border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                <span class="text-xs text-emerald-600 font-medium">✓ Completed</span>
                <button class="text-xs font-bold text-slate-400 hover:underline">Certificate</button>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     5. TRAINING MANAGEMENT
     ============================================================ --}}
<div id="tab-content-training" class="tab-content space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Training Management</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Browse live workshops, request budgets, and view scheduled sessions.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-all hover:-translate-y-0.5">
            Request Custom Training
        </button>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6">
        <h3 class="font-bold text-slate-900 dark:text-white text-lg mb-6">Upcoming Sessions</h3>
        <div class="space-y-6 relative before:absolute before:inset-y-1 before:left-3.5 before:w-0.5 before:bg-slate-100 dark:before:bg-zinc-800">
            <!-- Event 1 -->
            <div class="flex items-start gap-4 relative">
                <span class="w-7 h-7 rounded-full bg-blue-50 dark:bg-blue-950 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0 font-bold text-xs ring-4 ring-white dark:ring-zinc-900 z-10">1</span>
                <div class="flex-1 bg-slate-50 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-800 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">Technology</span>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm mt-1">Docker & Kubernetes Orchestration</h4>
                        <p class="text-xs text-slate-500 mt-0.5">July 15, 2026 · 2 Hours · Zoom Online</p>
                    </div>
                    <button class="px-3.5 py-1.5 bg-brand text-white rounded-lg text-xs font-semibold shrink-0">Enrolled</button>
                </div>
            </div>
            <!-- Event 2 -->
            <div class="flex items-start gap-4 relative">
                <span class="w-7 h-7 rounded-full bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0 font-bold text-xs ring-4 ring-white dark:ring-zinc-900 z-10">2</span>
                <div class="flex-1 bg-slate-50 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-800 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Soft Skills</span>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm mt-1">Constructive Feedback & Psychological Safety</h4>
                        <p class="text-xs text-slate-500 mt-0.5">July 29, 2026 · 3 Hours · Conference Room B</p>
                    </div>
                    <button class="px-3.5 py-1.5 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg text-xs font-semibold shrink-0">Join Waiting List</button>
                </div>
            </div>
            <!-- Event 3 -->
            <div class="flex items-start gap-4 relative">
                <span class="w-7 h-7 rounded-full bg-amber-50 dark:bg-amber-950 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0 font-bold text-xs ring-4 ring-white dark:ring-zinc-900 z-10">3</span>
                <div class="flex-1 bg-slate-50 dark:bg-zinc-800/40 border border-slate-100 dark:border-zinc-800 rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Compliance</span>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm mt-1">Data Privacy & GDPR 2026 Refresher</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Aug 5, 2026 · 1.5 Hours · Online</p>
                    </div>
                    <button class="px-3.5 py-1.5 border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-lg text-xs font-semibold shrink-0">Register</button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     6. SUCCESSION PLANNING
     ============================================================ --}}
<div id="tab-content-succession" class="tab-content space-y-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Succession Planning</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Map critical roles, manage talent pools, and mitigate organisational risk.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Role Card 1 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider bg-rose-50 dark:bg-rose-950/30 px-2 py-0.5 rounded">High Risk Role</span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mt-2">VP of Engineering</h3>
                </div>
                <span class="text-xs text-slate-400 whitespace-nowrap">Sarah Connor (Incumbent)</span>
            </div>
            <div class="border-t border-slate-100 dark:border-zinc-800 pt-4 space-y-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identified Successors</span>
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-zinc-800/40">
                    <div class="flex items-center gap-2.5">
                        <img class="w-8 h-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=80&h=80&q=80" alt="Emily">
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-zinc-200">Emily Watson</p>
                            <p class="text-[10px] text-slate-400">Engineering Manager</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-600">Ready Now</span>
                </div>
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-zinc-800/40">
                    <div class="flex items-center gap-2.5">
                        <img class="w-8 h-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=80&h=80&q=80" alt="David">
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-zinc-200">David Miller</p>
                            <p class="text-[10px] text-slate-400">Principal Architect</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-600">1–2 Years</span>
                </div>
            </div>
        </div>

        <!-- Role Card 2 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4">
            <div class="flex justify-between items-start gap-2">
                <div>
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider bg-blue-50 dark:bg-blue-950/30 px-2 py-0.5 rounded">Key Tech Role</span>
                    <h3 class="font-bold text-slate-900 dark:text-white text-lg mt-2">Chief System Architect</h3>
                </div>
                <span class="text-xs text-slate-400 whitespace-nowrap">Vacancy Pending</span>
            </div>
            <div class="border-t border-slate-100 dark:border-zinc-800 pt-4 space-y-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identified Successors</span>
                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-zinc-800/40">
                    <div class="flex items-center gap-2.5">
                        <img class="w-8 h-8 rounded-full object-cover" src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=80&h=80&q=80" alt="Alex">
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-zinc-200">Alex River</p>
                            <p class="text-[10px] text-slate-400">Senior Infrastructure Lead</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-600">Ready Now</span>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ============================================================
     7. SOCIAL RECOGNITION
     ============================================================ --}}
<div id="tab-content-recognition" class="tab-content space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Social Recognition Wall</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Shout-out and celebrate colleagues for displaying core values.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg text-sm font-semibold shadow-sm hover:shadow transition-all hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            Write Shout-Out
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Post 1 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=100&h=100&q=80" alt="Emily">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">Emily Watson</h4>
                        <p class="text-xs text-slate-400">Engineering Manager</p>
                    </div>
                </div>
                <span class="text-[10px] text-slate-400">2 hours ago</span>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/40 p-4 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                <p class="text-[10px] font-bold mb-1 text-blue-600 dark:text-blue-400 uppercase tracking-widest">🏆 Customer Focus</p>
                <p class="text-sm text-slate-700 dark:text-zinc-300 italic">"Shout-out to David Miller for working late Friday to deploy critical database migrations. Excellent customer support!"</p>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3 text-xs">
                <span class="text-slate-400">Received by: <strong class="text-slate-700 dark:text-zinc-200">David Miller</strong></span>
                <button onclick="incrementClap(this)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-500 rounded-full font-semibold hover:bg-rose-100 transition-colors">
                    👏 <span class="clap-count">12</span>
                </button>
            </div>
        </div>

        <!-- Post 2 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=100&h=100&q=80" alt="Marcus">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">Marcus Vance</h4>
                        <p class="text-xs text-slate-400">Operations Specialist</p>
                    </div>
                </div>
                <span class="text-[10px] text-slate-400">Yesterday</span>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/40 p-4 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                <p class="text-[10px] font-bold mb-1 text-rose-500 uppercase tracking-widest">💡 Collaboration Elite</p>
                <p class="text-sm text-slate-700 dark:text-zinc-300 italic">"Sarah Connor structured the succession mapping grids perfectly. Our talent depth is clearer than ever!"</p>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3 text-xs">
                <span class="text-slate-400">Received by: <strong class="text-slate-700 dark:text-zinc-200">Sarah Connor</strong></span>
                <button onclick="incrementClap(this)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-500 rounded-full font-semibold hover:bg-rose-100 transition-colors">
                    👏 <span class="clap-count">8</span>
                </button>
            </div>
        </div>

        <!-- Post 3 -->
        <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 space-y-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img class="w-10 h-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80" alt="David">
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">David Miller</h4>
                        <p class="text-xs text-slate-400">Principal Architect</p>
                    </div>
                </div>
                <span class="text-[10px] text-slate-400">2 days ago</span>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/40 p-4 rounded-xl border border-slate-100 dark:border-zinc-800/50">
                <p class="text-[10px] font-bold mb-1 text-emerald-600 uppercase tracking-widest">⭐ Innovation</p>
                <p class="text-sm text-slate-700 dark:text-zinc-300 italic">"Alex River proposed an incredible microservices solution that saved us 30% in compute costs. Truly innovative thinking!"</p>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 dark:border-zinc-800 pt-3 text-xs">
                <span class="text-slate-400">Received by: <strong class="text-slate-700 dark:text-zinc-200">Alex River</strong></span>
                <button onclick="incrementClap(this)" class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-500 rounded-full font-semibold hover:bg-rose-100 transition-colors">
                    👏 <span class="clap-count">24</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection