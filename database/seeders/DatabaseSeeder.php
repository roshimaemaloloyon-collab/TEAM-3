<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\Notification;
use App\Models\Performance;
use App\Models\PeerEvaluation;
use App\Models\Training;
use App\Models\User;
use App\Models\Report;
use App\Models\PerformanceHistory;
use App\Models\Competency;
use App\Models\CompetencyAssessment;
use App\Models\CompetencyHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        \DB::statement('PRAGMA foreign_keys = OFF');
        \DB::table('notifications')->truncate();
        \DB::table('peer_evaluations')->truncate();
        \DB::table('performances')->truncate();
        \DB::table('kpis')->truncate();
        \DB::table('performance_reviews')->truncate();
        \DB::table('performance_history')->truncate();
        \DB::table('reports')->truncate();
        \DB::table('trainings')->truncate();
        \DB::table('training_registrations')->truncate();
        \DB::table('attendance')->truncate();
        \DB::table('training_evaluations')->truncate();
        \DB::table('certificates')->truncate();
        \DB::table('drivers')->truncate();
        \DB::table('users')->truncate();
        \DB::table('report_exports')->truncate();
        \DB::table('report_history')->truncate();
        \DB::table('analytics_data')->truncate();
        \DB::table('performance_history')->truncate();
        \DB::table('competencies')->truncate();
        \DB::table('competency_assessments')->truncate();
        \DB::table('competency_development_plans')->truncate();
        \DB::table('competency_history')->truncate();
        \DB::table('sqlite_sequence')->where('name', 'drivers')->update(['seq' => 0]);
        \DB::statement('PRAGMA foreign_keys = ON');

        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@tripwise.app',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $defaultDriverUser = User::factory()->create([
            'name' => 'Juan Dela Cruz',
            'email' => 'driver@tripwise.app',
            'password' => bcrypt('password'),
            'role' => 'driver',
            'status' => 'active',
        ]);

        $users = User::factory(30)->create();
        $drivers = Driver::factory(35)->create();

        // Connect driver users with performance, KPI, and Performance Review records
        $driverUsers = User::where('role', 'driver')->get();
        if ($driverUsers->isEmpty()) {
            $driverUsers = collect([$defaultDriverUser]);
        }

        $kpiNames = [
            ['name' => 'Trip Completion Rate', 'cat' => 'efficiency', 'target' => 95.0],
            ['name' => 'Safety & Traffic Score', 'cat' => 'safety', 'target' => 98.0],
            ['name' => 'Customer Service Rating', 'cat' => 'customer_service', 'target' => 4.8],
            ['name' => 'On-Time Attendance', 'cat' => 'attendance', 'target' => 99.0],
        ];

        foreach ($driverUsers as $dUser) {
            Performance::factory()->create([
                'driver_id' => $dUser->id,
            ]);

            foreach ($kpiNames as $kpiInfo) {
                // Realistic variation: ~40% high achievers, ~35% in progress, ~25% needing improvement/missed
                $randType = fake()->randomElement(['high', 'medium', 'low']);
                if ($randType === 'high') {
                    $actual = fake()->randomFloat(2, 90.0, 99.5);
                    $status = 'achieved';
                } elseif ($randType === 'medium') {
                    $actual = fake()->randomFloat(2, 75.0, 89.9);
                    $status = 'in_progress';
                } else {
                    $actual = fake()->randomFloat(2, 45.0, 74.9);
                    $status = 'missed';
                }

                // If customer service rating (target 4.8), scale actual value appropriately to 1.0-5.0
                if ($kpiInfo['cat'] === 'customer_service') {
                    $actual = round(($actual / 100) * 5.0, 2);
                    $achieved = min(100.0, round(($actual / $kpiInfo['target']) * 100, 1));
                } else {
                    $achieved = min(100.0, round(($actual / $kpiInfo['target']) * 100, 1));
                }

                \App\Models\Kpi::create([
                    'driver_id' => $dUser->id,
                    'kpi_name' => $kpiInfo['name'],
                    'description' => 'Target threshold evaluation for ' . $kpiInfo['name'],
                    'target_value' => $kpiInfo['target'],
                    'actual_value' => $actual,
                    'achievement_percentage' => $achieved,
                    'status' => $status,
                    'kpi_category' => $kpiInfo['cat'],
                    'period_start' => now()->startOfMonth(),
                    'period_end' => now()->endOfMonth(),
                ]);
            }

            \App\Models\PerformanceReview::create([
                'driver_id' => $dUser->id,
                'review_type' => fake()->randomElement(['monthly', 'quarterly', 'annual']),
                'period' => 'Q3 2026',
                'review_date' => fake()->dateTimeBetween('-3 months', 'now'),
                'performance_score' => fake()->randomFloat(1, 3.5, 5.0),
                'admin_feedback' => 'Consistently meets operational safety standards and receives positive passenger ratings.',
                'recommendations' => 'Maintain steady attendance and complete advanced eco-driving modules.',
                'status' => fake()->randomElement(['completed', 'pending']),
                'reviewer_id' => $adminUser->id,
            ]);

            \App\Models\PerformanceHistory::create([
                'driver_id' => $dUser->id,
                'overall_score' => fake()->randomFloat(2, 3.5, 5.0),
                'kpi_score' => fake()->randomFloat(2, 75.0, 99.0),
                'ranking' => fake()->numberBetween(1, 50),
                'performance_status' => fake()->randomElement(['excellent', 'good', 'average', 'needs_improvement']),
                'record_type' => fake()->randomElement(['review', 'snapshot', 'kpi_update', 'ranking_change']),
                'notes' => 'Periodic operational log entry.',
                'recorded_at' => fake()->dateTimeBetween('-6 months', 'now'),
                'recorded_by' => $adminUser->id,
            ]);

            \App\Models\Report::create([
                'name' => $dUser->name . ' — Monthly Performance Summary',
                'category' => 'performance',
                'report_type' => fake()->randomElement(['individual', 'ranking', 'kpi', 'summary']),
                'export_format' => 'pdf',
                'status' => 'generated',
                'generated_by' => $adminUser->id,
                'generated_at' => fake()->dateTimeBetween('-1 month', 'now'),
            ]);
        }

        Performance::factory(20)->create();
        $trainings = Training::factory(25)->create();

        foreach ($driverUsers as $dUser) {
            foreach ($trainings->random(3) as $training) {
                \App\Models\TrainingRegistration::factory()->create([
                    'driver_id' => $dUser->id,
                    'training_id' => $training->id,
                ]);

                \App\Models\Attendance::factory()->create([
                    'driver_id' => $dUser->id,
                    'training_id' => $training->id,
                ]);

                \App\Models\TrainingEvaluation::factory()->create([
                    'driver_id' => $dUser->id,
                    'training_id' => $training->id,
                ]);
            }
        }

        PeerEvaluation::factory(40)->create();
        Notification::factory(50)->create();
        Report::factory(30)->create();
        PerformanceHistory::factory(40)->create();

        // ─────────────────────────────────────────────────────────────
        // Position-Based Learning Modules (Tripwise Operations & Staff)
        // ─────────────────────────────────────────────────────────────
        $roleModulesData = [
            // 1. MC TAXI DRIVER
            [
                'title' => 'MC Taxi Two-Wheeler Safety & Helmet Protocols',
                'category' => 'road_safety',
                'type' => 'course',
                'duration_minutes' => 45,
                'difficulty' => 'beginner',
                'target_position' => 'MC TAXI DRIVER',
                'description' => 'Essential balance, defensive cornering, passenger helmet hygiene, and two-wheeler traffic laws.'
            ],
            [
                'title' => 'Passenger Care & Weather Preparedness for MC Drivers',
                'category' => 'customer_service',
                'type' => 'video',
                'duration_minutes' => 30,
                'difficulty' => 'intermediate',
                'target_position' => 'MC TAXI DRIVER',
                'description' => 'Rainwear deployment, passenger comfort guidelines, and handling heavy traffic navigation.'
            ],

            // 2. 4-WHEEL CAR DRIVER
            [
                'title' => 'Safe 4-Wheel Driving & Highway Courtesy',
                'category' => 'defensive_driving',
                'type' => 'course',
                'duration_minutes' => 60,
                'difficulty' => 'beginner',
                'target_position' => '4-WHEEL CAR DRIVER',
                'description' => 'Comprehensive 4-wheel vehicle handling, blind spot monitoring, braking techniques, and tollway etiquette.'
            ],
            [
                'title' => 'Sedan & SUV Fleet Preventive Care',
                'category' => 'vehicle_maintenance',
                'type' => 'pdf',
                'duration_minutes' => 40,
                'difficulty' => 'intermediate',
                'target_position' => '4-WHEEL CAR DRIVER',
                'description' => 'Daily BLOWBAGETS checklist (Battery, Lights, Oil, Water, Brake, Air, Gas, Engine, Tire, Self).'
            ],

            // 3. OPERATIONS MANAGER
            [
                'title' => 'TNVS Fleet Management & Dispatch Operations',
                'category' => 'operations',
                'type' => 'course',
                'duration_minutes' => 120,
                'difficulty' => 'advanced',
                'target_position' => 'OPERATIONS MANAGER',
                'description' => 'Strategies for peak-hour dispatching, driver allocation efficiency, SLA monitoring, and surge pricing controls.'
            ],
            [
                'title' => 'Emergency Escalation & Incident Control',
                'category' => 'emergency_response',
                'type' => 'pdf',
                'duration_minutes' => 90,
                'difficulty' => 'advanced',
                'target_position' => 'OPERATIONS MANAGER',
                'description' => 'Protocol for managing vehicular accidents, insurance claims, passenger grievances, and crisis communication.'
            ],

            // 4. OFFICE STAFF
            [
                'title' => 'Tripwise Admin Portal & Documentation Systems',
                'category' => 'company_policies',
                'type' => 'course',
                'duration_minutes' => 45,
                'difficulty' => 'beginner',
                'target_position' => 'OFFICE STAFF',
                'description' => 'Mastering administrative documentation, filing driver records, and internal ticket handling.'
            ],
            [
                'title' => 'Inter-Departmental Communication & Data Privacy',
                'category' => 'company_policies',
                'type' => 'video',
                'duration_minutes' => 35,
                'difficulty' => 'intermediate',
                'target_position' => 'OFFICE STAFF',
                'description' => 'Protecting driver PII data and maintaining compliance under the Data Privacy Act.'
            ],

            // 5. HR MANAGER
            [
                'title' => 'Driver Relations, Performance Reviews & Labor Compliance',
                'category' => 'company_policies',
                'type' => 'course',
                'duration_minutes' => 90,
                'difficulty' => 'advanced',
                'target_position' => 'HR MANAGER',
                'description' => 'Conducting objective performance appraisals, managing disciplinary actions, and labor law compliance.'
            ],

            // 6. FACILITIES COORDINATOR
            [
                'title' => 'Terminal & Garage Safety Inspections',
                'category' => 'vehicle_maintenance',
                'type' => 'course',
                'duration_minutes' => 60,
                'difficulty' => 'intermediate',
                'target_position' => 'FACILITIES COORDINATOR',
                'description' => 'Maintaining depot cleanliness, EV charging stations, fuel storage safety, and facility security.'
            ],

            // 7. VEHICLE DISPATCHER
            [
                'title' => 'Real-Time GPS Tracking & Driver Routing Optimization',
                'category' => 'operations',
                'type' => 'course',
                'duration_minutes' => 50,
                'difficulty' => 'intermediate',
                'target_position' => 'VEHICLE DISPATCHER',
                'description' => 'Utilizing live GIS maps, monitoring driver shifts, and resolving delay alerts in real-time.'
            ],

            // 8. FINANCE OFFICER
            [
                'title' => 'Driver Payouts, Commission Audits & Revenue Accounting',
                'category' => 'company_policies',
                'type' => 'course',
                'duration_minutes' => 90,
                'difficulty' => 'advanced',
                'target_position' => 'FINANCE OFFICER',
                'description' => 'Auditing daily trip earnings, toll reimbursements, incentive distribution, and tax withholdings.'
            ],

            // 9. RECRUITMENT SPECIALIST
            [
                'title' => 'Driver Onboarding & License Verification Standards',
                'category' => 'company_policies',
                'type' => 'course',
                'duration_minutes' => 60,
                'difficulty' => 'intermediate',
                'target_position' => 'RECRUITMENT SPECIALIST',
                'description' => 'Authenticating LTO professional driver licenses, NBI clearances, medical physicals, and background checks.'
            ],
        ];

        $learningModules = collect();
        foreach ($roleModulesData as $mData) {
            $learningModules->push(\App\Models\LearningModule::create([
                'title' => $mData['title'],
                'slug' => \Illuminate\Support\Str::slug($mData['title']),
                'description' => $mData['description'],
                'category' => $mData['category'],
                'type' => $mData['type'],
                'duration_minutes' => $mData['duration_minutes'],
                'difficulty' => $mData['difficulty'],
                'status' => 'active',
                'metadata' => [
                    'target_position' => $mData['target_position'],
                    'source' => 'system_curriculum'
                ],
                'created_by' => $adminUser->id,
            ]));
        }

        foreach ($driverUsers as $dUser) {
            foreach ($learningModules->random(min(5, $learningModules->count())) as $module) {
                \App\Models\LearningAssignment::factory()->create([
                    'driver_id' => $dUser->id,
                    'learning_module_id' => $module->id,
                ]);

                \App\Models\LearningAssessment::factory()->create([
                    'driver_id' => $dUser->id,
                    'learning_module_id' => $module->id,
                ]);

                \App\Models\LearningHistory::factory()->create([
                    'driver_id' => $dUser->id,
                    'learning_module_id' => $module->id,
                ]);
            }
        }

        $competencies = Competency::factory(10)->create();

        foreach ($driverUsers as $dUser) {
            foreach ($competencies as $competency) {
                CompetencyAssessment::factory()->create([
                    'driver_id' => $dUser->id,
                    'competency_id' => $competency->id,
                    'score' => fake()->randomFloat(1, 40.0, 100.0),
                    'status' => fake()->randomElement(['pending', 'assessed', 'reviewed', 'archived']),
                    'assessed_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);

                CompetencyHistory::create([
                    'driver_id' => $dUser->id,
                    'competency_id' => $competency->id,
                    'score' => fake()->randomFloat(1, 40.0, 100.0),
                    'record_type' => fake()->randomElement(['assessment', 'plan_update', 'coaching', 'review']),
                    'notes' => fake()->optional()->sentence(),
                    'recorded_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    'recorded_by' => $adminUser->id,
                ]);
            }

            \App\Models\CompetencyDevelopmentPlan::create([
                'driver_id' => $dUser->id,
                'plan_name' => fake()->randomElement([
                    'Safe Driving Improvement Plan',
                    'Customer Service Enhancement',
                    'Communication Skills Development',
                    'Navigation Proficiency Plan',
                    'Professionalism Training',
                ]),
                'description' => fake()->sentence(),
                'assigned_competencies' => $competencies->random(3)->pluck('id')->toArray(),
                'assigned_trainings' => Training::inRandomOrder()->take(2)->pluck('id')->toArray(),
                'assigned_learning_modules' => Training::inRandomOrder()->take(2)->pluck('id')->toArray(),
                'coaching_sessions' => fake()->numberBetween(1, 5),
                'development_objectives' => fake()->sentence(),
                'completion_percentage' => fake()->numberBetween(0, 100),
                'target_completion_date' => fake()->dateTimeBetween('+1 month', '+6 months'),
                'hr_remarks' => fake()->optional()->sentence(),
                'status' => fake()->randomElement(['active', 'completed', 'on_hold', 'cancelled']),
                'created_by' => $adminUser->id,
                'updated_by' => $adminUser->id,
            ]);
        }
    }
}