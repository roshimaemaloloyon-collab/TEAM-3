@extends('admin.layouts.admin')

@section('title', 'TripWise — Competency Management')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('admin.dashboard') }}">Home</a>
    <span>/</span>
    <span>Competency Management</span>
</div>

<!-- Page Header -->
<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <div>
        <h1 style="font-size:1.75rem;color:var(--primary);margin:0 0 0.25rem;">Competency Management</h1>
        <p style="color:var(--text-muted);font-size:0.9rem;margin:0;">Track, assess, and develop driver competencies across all core skill areas.</p>
    </div>
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <button class="btn btn-secondary" onclick="exportReport('pdf')"><i class="fas fa-file-pdf"></i> Export PDF</button>
        <button class="btn btn-secondary" onclick="exportReport('excel')"><i class="fas fa-file-excel"></i> Export Excel</button>
        <button class="btn btn-primary" onclick="openModal('assessModal')"><i class="fas fa-plus"></i> New Assessment</button>
    </div>
</div>

<!-- Filter Toolbar -->
<div class="table-card" style="margin-bottom:1.5rem;padding:1rem 1.25rem;">
    <div class="filter-bar" style="margin-bottom:0;flex-wrap:wrap;">
        <select id="filterDriver" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:150px;">
            <option value="">All Drivers</option>
            <option>Juan Dela Cruz</option>
            <option>Maria Santos</option>
            <option>Pedro Reyes</option>
            <option>Ana Lim</option>
            <option>Rosa Garcia</option>
            <option>Luis Tan</option>
            <option>Elena Cruz</option>
        </select>
        <select id="filterSkill" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:160px;">
            <option value="">All Skills</option>
            <option>Safe Driving</option>
            <option>Customer Service</option>
            <option>Communication</option>
            <option>Navigation</option>
            <option>Professionalism</option>
            <option>Time Management</option>
            <option>Vehicle Care</option>
        </select>
        <select id="filterPeriod" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Periods</option>
            <option>Q1 2026</option>
            <option>Q2 2026</option>
            <option>Q3 2026</option>
            <option>Q4 2025</option>
        </select>
        <select id="filterStatus" style="padding:0.5rem 0.85rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.85rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;min-width:140px;">
            <option value="">All Statuses</option>
            <option>Excellent</option>
            <option>Proficient</option>
            <option>Developing</option>
            <option>Needs Coaching</option>
        </select>
        <div style="margin-left:auto;display:flex;gap:0.5rem;">
            <button class="btn btn-primary" onclick="applyFilters()" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-search"></i> Search</button>
            <button class="btn btn-secondary" onclick="resetFilters()" style="padding:0.5rem 1rem;font-size:0.85rem;"><i class="fas fa-undo"></i> Reset</button>
        </div>
    </div>
</div>

<!-- Skills Assessment Table -->
<div class="table-card" style="margin-bottom:2rem;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
        <h3 style="margin:0;font-size:1.1rem;color:var(--primary);display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-tasks" style="opacity:0.7;"></i> Skills Assessment — All Drivers
        </h3>
        <div style="display:flex;gap:0.5rem;">
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.8rem;" onclick="toggleView('table')"><i class="fas fa-list"></i> Table</button>
            <button class="btn btn-secondary" style="padding:0.4rem 0.8rem;font-size:0.8rem;" onclick="toggleView('cards')"><i class="fas fa-th"></i> Cards</button>
        </div>
    </div>

    <!-- Skill Category Tabs -->
    <div style="display:flex;gap:0.5rem;margin-bottom:1.25rem;flex-wrap:wrap;" id="skillTabs">
        <button class="skill-tab active" onclick="filterTab(this, 'all')">All Skills</button>
        <button class="skill-tab" onclick="filterTab(this, 'safe-driving')"><i class="fas fa-shield-alt"></i> Safe Driving</button>
        <button class="skill-tab" onclick="filterTab(this, 'customer-service')"><i class="fas fa-smile"></i> Customer Service</button>
        <button class="skill-tab" onclick="filterTab(this, 'communication')"><i class="fas fa-comments"></i> Communication</button>
        <button class="skill-tab" onclick="filterTab(this, 'navigation')"><i class="fas fa-map-marked-alt"></i> Navigation</button>
        <button class="skill-tab" onclick="filterTab(this, 'professionalism')"><i class="fas fa-user-tie"></i> Professionalism</button>
        <button class="skill-tab" onclick="filterTab(this, 'time-management')"><i class="fas fa-clock"></i> Time Mgmt</button>
        <button class="skill-tab" onclick="filterTab(this, 'vehicle-care')"><i class="fas fa-car"></i> Vehicle Care</button>
    </div>

    <div class="table-wrapper" id="assessmentTable">
        <table>
            <thead>
                <tr>
                    <th>Driver</th>
                    <th>Safe Driving</th>
                    <th>Customer Service</th>
                    <th>Communication</th>
                    <th>Navigation</th>
                    <th>Professionalism</th>
                    <th>Time Mgmt</th>
                    <th>Vehicle Care</th>
                    <th>Overall Score</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $drivers = [
                    ['name'=>'Juan Dela Cruz','safe'=>95,'cust'=>92,'comm'=>88,'nav'=>90,'prof'=>96,'time'=>85,'veh'=>91],
                    ['name'=>'Maria Santos','safe'=>88,'cust'=>97,'comm'=>94,'nav'=>85,'prof'=>91,'time'=>90,'veh'=>83],
                    ['name'=>'Pedro Reyes','safe'=>91,'cust'=>84,'comm'=>80,'nav'=>93,'prof'=>87,'time'=>88,'veh'=>95],
                    ['name'=>'Ana Lim','safe'=>86,'cust'=>90,'comm'=>89,'nav'=>87,'prof'=>92,'time'=>91,'veh'=>84],
                    ['name'=>'Rosa Garcia','safe'=>72,'cust'=>78,'comm'=>70,'nav'=>65,'prof'=>74,'time'=>68,'veh'=>77],
                    ['name'=>'Luis Tan','safe'=>80,'cust'=>69,'comm'=>75,'nav'=>83,'prof'=>78,'time'=>82,'veh'=>71],
                    ['name'=>'Elena Cruz','safe'=>84,'cust'=>82,'comm'=>86,'nav'=>80,'prof'=>85,'time'=>79,'veh'=>88],
                ];
                @endphp
                @foreach($drivers as $d)
                @php
                    $scores = [$d['safe'],$d['cust'],$d['comm'],$d['nav'],$d['prof'],$d['time'],$d['veh']];
                    $overall = round(array_sum($scores)/count($scores),1);
                    if($overall>=90){$status='Excellent';$statusClass='status-active';}
                    elseif($overall>=80){$status='Proficient';$statusClass='status-review';}
                    elseif($overall>=70){$status='Developing';$statusClass='status-pending';}
                    else{$status='Needs Coaching';$statusClass='status-inactive';}
                    $scoreColor = $overall>=90?'var(--success)':($overall>=80?'var(--info)':($overall>=70?'var(--warning)':'var(--danger)'));
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#ff8a65);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                                {{ strtoupper(substr($d['name'],0,1)) }}{{ strtoupper(substr(explode(' ',$d['name'])[1]??'',0,1)) }}
                            </div>
                            <strong style="font-size:0.88rem;">{{ $d['name'] }}</strong>
                        </div>
                    </td>
                    <td><span class="score-pill {{ $d['safe']>=85?'pill-green':($d['safe']>=75?'pill-blue':'pill-red') }}">{{ $d['safe'] }}%</span></td>
                    <td><span class="score-pill {{ $d['cust']>=85?'pill-green':($d['cust']>=75?'pill-blue':'pill-red') }}">{{ $d['cust'] }}%</span></td>
                    <td><span class="score-pill {{ $d['comm']>=85?'pill-green':($d['comm']>=75?'pill-blue':'pill-red') }}">{{ $d['comm'] }}%</span></td>
                    <td><span class="score-pill {{ $d['nav']>=85?'pill-green':($d['nav']>=75?'pill-blue':'pill-red') }}">{{ $d['nav'] }}%</span></td>
                    <td><span class="score-pill {{ $d['prof']>=85?'pill-green':($d['prof']>=75?'pill-blue':'pill-red') }}">{{ $d['prof'] }}%</span></td>
                    <td><span class="score-pill {{ $d['time']>=85?'pill-green':($d['time']>=75?'pill-blue':'pill-red') }}">{{ $d['time'] }}%</span></td>
                    <td><span class="score-pill {{ $d['veh']>=85?'pill-green':($d['veh']>=75?'pill-blue':'pill-red') }}">{{ $d['veh'] }}%</span></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <div style="flex:1;height:6px;background:var(--border);border-radius:3px;overflow:hidden;">
                                <div style="height:100%;width:{{ $overall }}%;background:{{ $scoreColor }};border-radius:3px;transition:width 0.6s ease;"></div>
                            </div>
                            <strong style="font-size:0.85rem;color:{{ $scoreColor }};min-width:40px;">{{ $overall }}%</strong>
                        </div>
                    </td>
                    <td><span class="status-badge {{ $statusClass }}">{{ $status }}</span></td>
                    <td>
                        <div style="display:flex;gap:0.35rem;">
                            <button class="icon-action-btn" title="View Profile" onclick="viewDriver('{{ $d['name'] }}')"><i class="fas fa-eye"></i></button>
                            <button class="icon-action-btn" title="Edit Assessment" onclick="editAssessment('{{ $d['name'] }}')"><i class="fas fa-edit"></i></button>
                            <button class="icon-action-btn icon-action-red" title="View Plan" onclick="viewPlan('{{ $d['name'] }}')"><i class="fas fa-clipboard-list"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Competency Score + Strengths/Weaknesses + Improvement Plan Grid -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;" class="comp-detail-grid">

    <!-- Competency Score Panel -->
    <div class="table-card" style="margin-bottom:0;">
        <h3 style="font-size:1.1rem;color:var(--primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-tachometer-alt" style="opacity:0.7;"></i> Competency Scores — Proficiency Index
        </h3>
        @php
        $competencies = [
            ['name'=>'Safe Driving','icon'=>'fas fa-shield-alt','color'=>'blue','current'=>4.5,'target'=>5.0,'pct'=>90],
            ['name'=>'Customer Service','icon'=>'fas fa-smile','color'=>'green','current'=>4.3,'target'=>5.0,'pct'=>86],
            ['name'=>'Communication','icon'=>'fas fa-comments','color'=>'purple','current'=>4.0,'target'=>5.0,'pct'=>80],
            ['name'=>'Navigation','icon'=>'fas fa-map-marked-alt','color'=>'teal','current'=>4.2,'target'=>5.0,'pct'=>84],
            ['name'=>'Professionalism','icon'=>'fas fa-user-tie','color'=>'gold','current'=>4.4,'target'=>5.0,'pct'=>88],
            ['name'=>'Time Management','icon'=>'fas fa-clock','color'=>'orange','current'=>3.9,'target'=>5.0,'pct'=>78],
            ['name'=>'Vehicle Care','icon'=>'fas fa-car','color'=>'blue','current'=>4.1,'target'=>5.0,'pct'=>82],
        ];
        $colorMap=['blue'=>'#3b82f6','green'=>'#10b981','purple'=>'#8b5cf6','teal'=>'#14b8a6','gold'=>'#f59e0b','orange'=>'#f97316'];
        @endphp
        <div style="display:flex;flex-direction:column;gap:1.1rem;">
            @foreach($competencies as $comp)
            @php
                $col = $colorMap[$comp['color']] ?? '#3b82f6';
                $gap = round(($comp['target'] - $comp['current']) * 10) / 10;
            @endphp
            <div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.3rem;">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div class="card-icon {{ $comp['color'] }}" style="width:32px;height:32px;border-radius:0.5rem;font-size:0.85rem;">
                            <i class="{{ $comp['icon'] }}"></i>
                        </div>
                        <span style="font-weight:600;font-size:0.88rem;">{{ $comp['name'] }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <span style="font-size:0.8rem;color:var(--text-muted);">{{ $comp['current'] }}/{{ $comp['target'] }}</span>
                        @if($gap > 0)
                            <span class="status-badge status-pending" style="font-size:0.7rem;padding:0.15rem 0.5rem;">Gap: {{ $gap }}</span>
                        @else
                            <span class="status-badge status-active" style="font-size:0.7rem;padding:0.15rem 0.5rem;">Met</span>
                        @endif
                    </div>
                </div>
                <div style="height:8px;background:var(--border);border-radius:4px;overflow:hidden;">
                    <div class="animated-bar" style="height:100%;width:{{ $comp['pct'] }}%;background:{{ $col }};border-radius:4px;transition:width 0.8s ease;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Strengths & Weaknesses Panel -->
    <div class="table-card" style="margin-bottom:0;">
        <h3 style="font-size:1.1rem;color:var(--primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-balance-scale" style="opacity:0.7;"></i> Team Strengths &amp; Weaknesses
        </h3>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <!-- Strengths -->
            <div>
                <div style="font-weight:700;font-size:0.85rem;color:var(--success);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                    <i class="fas fa-thumbs-up"></i> Top Strengths
                </div>
                <div style="display:flex;flex-direction:column;gap:0.6rem;">
                    @php
                    $strengths = [
                        ['label'=>'Safe Driving','score'=>90,'icon'=>'fas fa-shield-alt'],
                        ['label'=>'Professionalism','score'=>88,'icon'=>'fas fa-user-tie'],
                        ['label'=>'Customer Service','score'=>86,'icon'=>'fas fa-smile'],
                        ['label'=>'Navigation','score'=>84,'icon'=>'fas fa-map-marked-alt'],
                    ];
                    @endphp
                    @foreach($strengths as $s)
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:0.6rem;padding:0.6rem 0.75rem;display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;font-weight:600;color:#065f46;">
                            <i class="{{ $s['icon'] }}" style="font-size:0.8rem;"></i> {{ $s['label'] }}
                        </div>
                        <span style="font-weight:700;font-size:0.82rem;color:#10b981;">{{ $s['score'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Weaknesses -->
            <div>
                <div style="font-weight:700;font-size:0.85rem;color:var(--danger);margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
                    <i class="fas fa-thumbs-down"></i> Areas for Growth
                </div>
                <div style="display:flex;flex-direction:column;gap:0.6rem;">
                    @php
                    $weaknesses = [
                        ['label'=>'Time Management','score'=>78,'icon'=>'fas fa-clock'],
                        ['label'=>'Vehicle Care','score'=>82,'icon'=>'fas fa-car'],
                        ['label'=>'Communication','score'=>80,'icon'=>'fas fa-comments'],
                        ['label'=>'Rosa Garcia - Nav','score'=>65,'icon'=>'fas fa-map-marker-alt'],
                    ];
                    @endphp
                    @foreach($weaknesses as $w)
                    <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:0.6rem;padding:0.6rem 0.75rem;display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;font-weight:600;color:#991b1b;">
                            <i class="{{ $w['icon'] }}" style="font-size:0.8rem;"></i> {{ $w['label'] }}
                        </div>
                        <span style="font-weight:700;font-size:0.82rem;color:var(--danger);">{{ $w['score'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Doughnut insight -->
        <div style="display:flex;align-items:center;gap:1rem;">
            <div style="position:relative;width:100px;height:100px;flex-shrink:0;">
                <canvas id="swDonut"></canvas>
                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;">
                    <span style="font-size:1.1rem;font-weight:800;color:var(--primary);">82%</span>
                    <span style="font-size:0.65rem;color:var(--text-muted);">Overall</span>
                </div>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700;font-size:0.9rem;margin-bottom:0.4rem;">Team Health Index</div>
                <p style="font-size:0.82rem;color:var(--text-muted);line-height:1.5;margin:0;">Overall competency health is <strong style="color:var(--success);">Good</strong>. 3 competencies are at target. Time Management needs the most attention — recommend scheduling a focused workshop.</p>
            </div>
        </div>
    </div>
</div>

<!-- Improvement Plans + Competency History -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;" class="comp-detail-grid">

    <!-- Improvement Plans -->
    <div class="table-card" style="margin-bottom:0;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.5rem;">
            <h3 style="margin:0;font-size:1.1rem;color:var(--primary);display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-clipboard-list" style="opacity:0.7;"></i> Active Improvement Plans
            </h3>
            <button class="btn btn-primary" style="padding:0.4rem 0.85rem;font-size:0.8rem;" onclick="openModal('planModal')"><i class="fas fa-plus"></i> Add Plan</button>
        </div>
        @php
        $plans = [
            ['driver'=>'Rosa Garcia','skill'=>'Navigation','target'=>'75%','current'=>65,'due'=>'Aug 15, 2026','status'=>'In Progress','color'=>'status-pending'],
            ['driver'=>'Rosa Garcia','skill'=>'Time Management','target'=>'75%','current'=>68,'due'=>'Aug 15, 2026','status'=>'In Progress','color'=>'status-pending'],
            ['driver'=>'Luis Tan','skill'=>'Customer Service','target'=>'80%','current'=>69,'due'=>'Aug 30, 2026','status'=>'In Progress','color'=>'status-pending'],
            ['driver'=>'Luis Tan','skill'=>'Vehicle Care','target'=>'80%','current'=>71,'due'=>'Sep 1, 2026','status'=>'On Track','color'=>'status-review'],
            ['driver'=>'Elena Cruz','skill'=>'Vehicle Care','target'=>'90%','current'=>88,'due'=>'Jul 31, 2026','status'=>'Near Goal','color'=>'status-active'],
            ['driver'=>'Pedro Reyes','skill'=>'Communication','target'=>'85%','current'=>80,'due'=>'Aug 20, 2026','status'=>'On Track','color'=>'status-review'],
        ];
        @endphp
        <div style="display:flex;flex-direction:column;gap:0.9rem;">
            @foreach($plans as $plan)
            @php $progress = min(100, round(($plan['current'] / intval($plan['target'])) * 100)); @endphp
            <div style="border:1px solid var(--border);border-radius:0.75rem;padding:0.85rem 1rem;transition:box-shadow 0.2s;" class="plan-item">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:0.5rem;gap:0.5rem;">
                    <div>
                        <div style="font-weight:700;font-size:0.88rem;color:var(--text-dark);">{{ $plan['driver'] }}</div>
                        <div style="font-size:0.78rem;color:var(--text-muted);">Focus: <strong>{{ $plan['skill'] }}</strong> · Due: {{ $plan['due'] }}</div>
                    </div>
                    <span class="status-badge {{ $plan['color'] }}" style="font-size:0.72rem;white-space:nowrap;">{{ $plan['status'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <div style="flex:1;height:7px;background:var(--border);border-radius:4px;overflow:hidden;">
                        <div style="height:100%;width:{{ $plan['current'] }}%;background:linear-gradient(90deg,var(--primary),#ff8a65);border-radius:4px;transition:width 0.6s;"></div>
                    </div>
                    <span style="font-size:0.78rem;font-weight:700;color:var(--primary);min-width:60px;text-align:right;">{{ $plan['current'] }}% / {{ $plan['target'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Competency History -->
    <div class="table-card" style="margin-bottom:0;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.5rem;">
            <h3 style="margin:0;font-size:1.1rem;color:var(--primary);display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-history" style="opacity:0.7;"></i> Competency History Log
            </h3>
            <select style="padding:0.4rem 0.8rem;border:1px solid var(--border);border-radius:0.5rem;font-size:0.8rem;background:var(--white);color:var(--text-dark);font-family:'Inter',sans-serif;">
                <option>All Drivers</option>
                <option>Juan Dela Cruz</option>
                <option>Maria Santos</option>
                <option>Rosa Garcia</option>
            </select>
        </div>
        @php
        $history = [
            ['date'=>'Jul 10, 2026','driver'=>'Juan Dela Cruz','skill'=>'Safe Driving','old'=>92,'new'=>95,'change'=>'+3','dir'=>'up'],
            ['date'=>'Jul 8, 2026','driver'=>'Rosa Garcia','skill'=>'Navigation','old'=>70,'new'=>65,'change'=>'-5','dir'=>'down'],
            ['date'=>'Jul 5, 2026','driver'=>'Maria Santos','skill'=>'Customer Service','old'=>94,'new'=>97,'change'=>'+3','dir'=>'up'],
            ['date'=>'Jul 3, 2026','driver'=>'Luis Tan','skill'=>'Customer Service','old'=>73,'new'=>69,'change'=>'-4','dir'=>'down'],
            ['date'=>'Jun 30, 2026','driver'=>'Pedro Reyes','skill'=>'Navigation','old'=>90,'new'=>93,'change'=>'+3','dir'=>'up'],
            ['date'=>'Jun 28, 2026','driver'=>'Ana Lim','skill'=>'Time Management','old'=>88,'new'=>91,'change'=>'+3','dir'=>'up'],
            ['date'=>'Jun 25, 2026','driver'=>'Elena Cruz','skill'=>'Vehicle Care','old'=>84,'new'=>88,'change'=>'+4','dir'=>'up'],
            ['date'=>'Jun 22, 2026','driver'=>'Rosa Garcia','skill'=>'Time Management','old'=>72,'new'=>68,'change'=>'-4','dir'=>'down'],
        ];
        @endphp
        <div style="display:flex;flex-direction:column;gap:0;">
            @foreach($history as $h)
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.7rem 0;border-bottom:1px solid var(--border);" class="history-row">
                <div style="width:8px;height:8px;border-radius:50%;background:{{ $h['dir']==='up'?'var(--success)':'var(--danger)' }};flex-shrink:0;"></div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:0.84rem;color:var(--text-dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $h['driver'] }} — <span style="color:var(--text-muted);font-weight:400;">{{ $h['skill'] }}</span>
                    </div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $h['date'] }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <span style="font-size:0.8rem;color:var(--text-muted);">{{ $h['old'] }}%</span>
                    <i class="fas fa-arrow-right" style="font-size:0.7rem;color:var(--text-muted);"></i>
                    <span style="font-weight:700;font-size:0.84rem;color:var(--text-dark);">{{ $h['new'] }}%</span>
                    <span style="font-weight:700;font-size:0.8rem;padding:0.15rem 0.5rem;border-radius:2rem;background:{{ $h['dir']==='up'?'#d1fae5':'#fee2e2' }};color:{{ $h['dir']==='up'?'#065f46':'#991b1b' }};">
                        {{ $h['change'] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:1rem;">
            <button class="btn btn-secondary" style="font-size:0.82rem;padding:0.45rem 1.2rem;"><i class="fas fa-chevron-down"></i> Load More</button>
        </div>
    </div>
</div>

<!-- Skill-Level Proficiency Index Table -->
<div class="table-card" style="margin-bottom:2rem;">
    <h3 style="font-size:1.1rem;color:var(--primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
        <i class="fas fa-layer-group" style="opacity:0.7;"></i> Competency Proficiency Index — Level Overview
    </h3>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Competency</th>
                    <th>Skill Icon</th>
                    <th>Avg Level</th>
                    <th>Target Level</th>
                    <th>Gap</th>
                    <th>Drivers at Target</th>
                    <th>Drivers Below</th>
                    <th>Status</th>
                    <th>Trend</th>
                </tr>
            </thead>
            <tbody>
                @php
                $levelData = [
                    ['skill'=>'Safe Driving','icon'=>'fas fa-shield-alt','avg'=>4.5,'target'=>5.0,'gap'=>0.5,'atTarget'=>58,'below'=>6,'status'=>'Coaching','statusClass'=>'status-pending','trend'=>'up'],
                    ['skill'=>'Customer Service','icon'=>'fas fa-smile','avg'=>4.3,'target'=>5.0,'gap'=>0.7,'atTarget'=>52,'below'=>12,'status'=>'Training','statusClass'=>'status-pending','trend'=>'up'],
                    ['skill'=>'Communication','icon'=>'fas fa-comments','avg'=>4.0,'target'=>5.0,'gap'=>1.0,'atTarget'=>44,'below'=>20,'status'=>'Training','statusClass'=>'status-pending','trend'=>'stable'],
                    ['skill'=>'Navigation','icon'=>'fas fa-map-marked-alt','avg'=>4.2,'target'=>4.5,'gap'=>0.3,'atTarget'=>55,'below'=>9,'status'=>'Near Goal','statusClass'=>'status-review','trend'=>'up'],
                    ['skill'=>'Professionalism','icon'=>'fas fa-user-tie','avg'=>4.4,'target'=>4.5,'gap'=>0.1,'atTarget'=>61,'below'=>3,'status'=>'Near Goal','statusClass'=>'status-review','trend'=>'up'],
                    ['skill'=>'Time Management','icon'=>'fas fa-clock','avg'=>3.9,'target'=>4.5,'gap'=>0.6,'atTarget'=>40,'below'=>24,'status'=>'Coaching','statusClass'=>'status-inactive','trend'=>'down'],
                    ['skill'=>'Vehicle Care','icon'=>'fas fa-car','avg'=>4.1,'target'=>4.5,'gap'=>0.4,'atTarget'=>48,'below'=>16,'status'=>'Training','statusClass'=>'status-pending','trend'=>'stable'],
                ];
                @endphp
                @foreach($levelData as $l)
                <tr>
                    <td><strong>{{ $l['skill'] }}</strong></td>
                    <td><i class="{{ $l['icon'] }}" style="color:var(--primary);font-size:1rem;"></i></td>
                    <td><strong>{{ $l['avg'] }}/5.0</strong></td>
                    <td>{{ $l['target'] }}/5.0</td>
                    <td>
                        @if($l['gap'] > 0)
                            <span style="color:var(--danger);font-weight:600;">-{{ $l['gap'] }}</span>
                        @else
                            <span style="color:var(--success);font-weight:600;">✓</span>
                        @endif
                    </td>
                    <td><span style="font-weight:600;color:var(--success);">{{ $l['atTarget'] }}</span></td>
                    <td><span style="font-weight:600;color:var(--danger);">{{ $l['below'] }}</span></td>
                    <td><span class="status-badge {{ $l['statusClass'] }}">{{ $l['status'] }}</span></td>
                    <td>
                        @if($l['trend'] === 'up')
                            <span style="color:var(--success);font-weight:700;"><i class="fas fa-arrow-up"></i> Up</span>
                        @elseif($l['trend'] === 'down')
                            <span style="color:var(--danger);font-weight:700;"><i class="fas fa-arrow-down"></i> Down</span>
                        @else
                            <span style="color:var(--text-muted);font-weight:700;"><i class="fas fa-minus"></i> Stable</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ─── MODALS ──────────────────────────────────────────────── -->

<!-- New Assessment Modal -->
<div id="assessModal" class="modal-overlay" onclick="closeOnOverlay(event,'assessModal')">
    <div class="modal-box" style="max-width:640px;">
        <div class="modal-header">
            <h3><i class="fas fa-clipboard-check"></i> New Skills Assessment</h3>
            <button class="modal-close" onclick="closeModal('assessModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Driver</label>
                    <select class="form-input">
                        <option>Select Driver</option>
                        <option>Juan Dela Cruz</option>
                        <option>Maria Santos</option>
                        <option>Pedro Reyes</option>
                        <option>Ana Lim</option>
                        <option>Rosa Garcia</option>
                        <option>Luis Tan</option>
                        <option>Elena Cruz</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Assessment Period</label>
                    <select class="form-input">
                        <option>Q3 2026</option>
                        <option>Q2 2026</option>
                        <option>Q1 2026</option>
                    </select>
                </div>
            </div>
            <div style="font-weight:700;font-size:0.9rem;margin-bottom:0.75rem;color:var(--text-dark);">Skill Scores (0–100)</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
                @php
                $skillFields = [
                    ['id'=>'scoreSafeDriving','label'=>'Safe Driving','icon'=>'fas fa-shield-alt'],
                    ['id'=>'scoreCustomerService','label'=>'Customer Service','icon'=>'fas fa-smile'],
                    ['id'=>'scoreCommunication','label'=>'Communication','icon'=>'fas fa-comments'],
                    ['id'=>'scoreNavigation','label'=>'Navigation','icon'=>'fas fa-map-marked-alt'],
                    ['id'=>'scoreProfessionalism','label'=>'Professionalism','icon'=>'fas fa-user-tie'],
                    ['id'=>'scoreTimeManagement','label'=>'Time Management','icon'=>'fas fa-clock'],
                    ['id'=>'scoreVehicleCare','label'=>'Vehicle Care','icon'=>'fas fa-car'],
                ];
                @endphp
                @foreach($skillFields as $sf)
                <div>
                    <label class="form-label"><i class="{{ $sf['icon'] }}" style="margin-right:0.35rem;color:var(--primary);"></i>{{ $sf['label'] }}</label>
                    <input type="number" id="{{ $sf['id'] }}" class="form-input" min="0" max="100" placeholder="e.g. 85" oninput="updateLiveScore()">
                </div>
                @endforeach
            </div>
            <div style="margin-top:1rem;padding:0.75rem 1rem;background:var(--beige);border-radius:0.65rem;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:600;font-size:0.9rem;">Computed Overall Score</span>
                <span id="liveScore" style="font-size:1.3rem;font-weight:800;color:var(--primary);">—</span>
            </div>
            <div style="margin-top:1rem;">
                <label class="form-label">Assessor Notes</label>
                <textarea class="form-input" rows="3" placeholder="Add observations, recommendations, or remarks..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('assessModal')">Cancel</button>
            <button class="btn btn-primary" onclick="saveAssessment()"><i class="fas fa-save"></i> Save Assessment</button>
        </div>
    </div>
</div>

<!-- Improvement Plan Modal -->
<div id="planModal" class="modal-overlay" onclick="closeOnOverlay(event,'planModal')">
    <div class="modal-box" style="max-width:560px;">
        <div class="modal-header">
            <h3><i class="fas fa-clipboard-list"></i> Create Improvement Plan</h3>
            <button class="modal-close" onclick="closeModal('planModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label class="form-label">Driver</label>
                    <select class="form-input">
                        <option>Select Driver</option>
                        <option>Rosa Garcia</option>
                        <option>Luis Tan</option>
                        <option>Elena Cruz</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Target Skill</label>
                    <select class="form-input">
                        <option>Select Skill</option>
                        <option>Safe Driving</option>
                        <option>Customer Service</option>
                        <option>Communication</option>
                        <option>Navigation</option>
                        <option>Professionalism</option>
                        <option>Time Management</option>
                        <option>Vehicle Care</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Current Score (%)</label>
                    <input type="number" class="form-input" placeholder="e.g. 68" min="0" max="100">
                </div>
                <div>
                    <label class="form-label">Target Score (%)</label>
                    <input type="number" class="form-input" placeholder="e.g. 80" min="0" max="100">
                </div>
                <div>
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-input">
                </div>
                <div>
                    <label class="form-label">Due Date</label>
                    <input type="date" class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Action Steps</label>
                <textarea class="form-input" rows="4" placeholder="Describe specific training modules, coaching sessions, or mentorship activities..."></textarea>
            </div>
            <div style="margin-top:1rem;">
                <label class="form-label">Assigned Coach / Supervisor</label>
                <input type="text" class="form-input" placeholder="Name of assigned coach">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('planModal')">Cancel</button>
            <button class="btn btn-primary"><i class="fas fa-save"></i> Create Plan</button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" style="position:fixed;bottom:2rem;right:2rem;background:var(--charcoal);color:#fff;padding:0.85rem 1.5rem;border-radius:0.75rem;box-shadow:0 8px 24px rgba(0,0,0,0.2);font-size:0.9rem;font-weight:500;display:none;align-items:center;gap:0.75rem;z-index:9999;animation:slideUp 0.3s ease;">
    <i class="fas fa-check-circle" style="color:#10b981;font-size:1rem;"></i>
    <span id="toastMsg">Action completed.</span>
</div>

<!-- Page-specific Styles -->
<style>
    .comp-detail-grid { grid-template-columns: 1fr 1fr; }
    @media (max-width: 900px) { .comp-detail-grid { grid-template-columns: 1fr; } }

    .score-pill {
        display:inline-block;
        padding:0.2rem 0.55rem;
        border-radius:2rem;
        font-size:0.78rem;
        font-weight:700;
    }
    .pill-green { background:#d1fae5;color:#065f46; }
    .pill-blue { background:#dbeafe;color:#1e40af; }
    .pill-red { background:#fee2e2;color:#991b1b; }

    .skill-tab {
        background:var(--beige);
        border:1px solid var(--border);
        border-radius:2rem;
        padding:0.35rem 0.9rem;
        font-size:0.8rem;
        font-weight:600;
        cursor:pointer;
        color:var(--text-muted);
        display:inline-flex;
        align-items:center;
        gap:0.4rem;
        transition:all 0.2s;
        font-family:'Inter',sans-serif;
    }
    .skill-tab:hover { background:var(--white);color:var(--primary);border-color:var(--primary); }
    .skill-tab.active { background:var(--primary);color:#fff;border-color:var(--primary); }

    .icon-action-btn {
        width:30px;height:30px;border-radius:0.45rem;border:1px solid var(--border);
        background:var(--white);color:var(--text-muted);cursor:pointer;
        display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;
        transition:all 0.2s;
    }
    .icon-action-btn:hover { background:var(--primary);color:#fff;border-color:var(--primary); }
    .icon-action-red:hover { background:#ef4444;border-color:#ef4444;color:#fff; }

    .plan-item:hover { box-shadow:0 4px 16px rgba(0,0,0,0.08); }
    .history-row:hover { background:var(--beige); }

    /* Modal Styles */
    .modal-overlay {
        display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);
        z-index:2000;align-items:center;justify-content:center;padding:1rem;
        backdrop-filter:blur(2px);
    }
    .modal-overlay.active { display:flex; }
    .modal-box {
        background:var(--white);border-radius:1rem;width:100%;max-height:90vh;
        overflow-y:auto;box-shadow:0 24px 64px rgba(0,0,0,0.2);
        animation:modalIn 0.25s ease;
    }
    @keyframes modalIn { from{opacity:0;transform:scale(0.95) translateY(10px)} to{opacity:1;transform:scale(1) translateY(0)} }
    @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .modal-header {
        display:flex;align-items:center;justify-content:space-between;
        padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);
    }
    .modal-header h3 { font-size:1.1rem;color:var(--primary);display:flex;align-items:center;gap:0.5rem; }
    .modal-close {
        background:none;border:none;font-size:1.1rem;color:var(--text-muted);
        cursor:pointer;padding:0.25rem;border-radius:0.35rem;transition:color 0.2s;
    }
    .modal-close:hover { color:var(--danger); }
    .modal-body { padding:1.5rem; }
    .modal-footer { padding:1rem 1.5rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem; }
    .form-label { display:block;font-size:0.82rem;font-weight:600;color:var(--text-dark);margin-bottom:0.35rem; }
    .form-input {
        width:100%;padding:0.55rem 0.85rem;border:1px solid var(--border);border-radius:0.55rem;
        font-size:0.88rem;font-family:'Inter',sans-serif;color:var(--text-dark);
        background:var(--white);transition:border-color 0.2s,box-shadow 0.2s;
    }
    .form-input:focus { outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(244,67,54,0.1); }

    .status-success { background: #d1fae5; color: #065f46; }
    .status-warning { background: #ffedd5; color: #c2410c; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Modal helpers ─────────────────────────────────────────────
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function closeOnOverlay(e, id) { if (e.target.id === id) closeModal(id); }

// ── Live score calculator ──────────────────────────────────────
function updateLiveScore() {
    const ids = ['scoreSafeDriving','scoreCustomerService','scoreCommunication','scoreNavigation','scoreProfessionalism','scoreTimeManagement','scoreVehicleCare'];
    const vals = ids.map(id => parseFloat(document.getElementById(id)?.value) || null).filter(v => v !== null);
    if (vals.length === 0) { document.getElementById('liveScore').textContent = '—'; return; }
    const avg = (vals.reduce((a,b)=>a+b,0)/vals.length).toFixed(1);
    const el = document.getElementById('liveScore');
    el.textContent = avg + '%';
    el.style.color = avg >= 90 ? 'var(--success)' : avg >= 80 ? 'var(--info)' : avg >= 70 ? 'var(--warning)' : 'var(--danger)';
}

// ── Save assessment ────────────────────────────────────────────
function saveAssessment() {
    closeModal('assessModal');
    showToast('Assessment saved successfully!');
}

// ── Skill tabs ────────────────────────────────────────────────
function filterTab(btn, skill) {
    document.querySelectorAll('.skill-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    // In a real app, filter table rows by skill here
}

// ── Toggle view ───────────────────────────────────────────────
function toggleView(mode) { /* future card/table toggle */ }

// ── View driver ───────────────────────────────────────────────
function viewDriver(name) { window.location.href = '/admin/drivers/profile/1'; }
function editAssessment(name) { openModal('assessModal'); }
function viewPlan(name) { openModal('planModal'); }

// ── Filters ───────────────────────────────────────────────────
function applyFilters() { showToast('Filters applied.'); }
function resetFilters() {
    ['filterDriver','filterSkill','filterPeriod','filterStatus'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.selectedIndex = 0;
    });
    showToast('Filters reset.');
}

// ── Export ────────────────────────────────────────────────────
function exportReport(type) {
    showToast('Exporting ' + type.toUpperCase() + ' report...');
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.style.display = 'flex';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
}
</script>
@endsection
