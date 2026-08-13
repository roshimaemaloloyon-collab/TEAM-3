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
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
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
        \DB::table('competencies')->truncate();
        \DB::table('competency_assessments')->truncate();
        \DB::table('competency_development_plans')->truncate();
        \DB::table('competency_history')->truncate();
        Schema::enableForeignKeyConstraints();

        $adminUser = User::factory()->create([
            'name' => 'TripWise Admin',
            'email' => 'admin@tripwise.app',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $filipinoDriverNames = [
            ['first' => 'Juan', 'last' => 'Dela Cruz', 'middle' => 'Perez', 'v_type' => 'Motorcycle', 'v_name' => 'Yamaha NMAX 155'],
            ['first' => 'Maria', 'last' => 'Santos', 'middle' => 'Reyes', 'v_type' => 'Sedan', 'v_name' => 'Toyota Vios 1.3 E'],
            ['first' => 'Pedro', 'last' => 'Penduko', 'middle' => 'Garcia', 'v_type' => 'Motorcycle', 'v_name' => 'Honda Click 150i'],
            ['first' => 'Ricardo', 'last' => 'Dalisay', 'middle' => 'Valdez', 'v_type' => 'SUV', 'v_name' => 'Mitsubishi Montero Sport'],
            ['first' => 'Danilo', 'last' => 'Reyes', 'middle' => 'Santos', 'v_type' => 'Motorcycle', 'v_name' => 'Honda ADV 160'],
            ['first' => 'Mark Anthony', 'last' => 'Santos', 'middle' => 'Cruz', 'v_type' => 'Sedan', 'v_name' => 'Honda Civic 1.5 Turbo'],
            ['first' => 'Christian', 'last' => 'Ramos', 'middle' => 'Bautista', 'v_type' => 'Motorcycle', 'v_name' => 'Yamaha Aerox 155'],
            ['first' => 'John Paul', 'last' => 'Garcia', 'middle' => 'Mendoza', 'v_type' => 'Van', 'v_name' => 'Toyota Hiace Commuter'],
            ['first' => 'Alexander', 'last' => 'Cruz', 'middle' => 'Flores', 'v_type' => 'Motorcycle', 'v_name' => 'Suzuki Burgman Street'],
            ['first' => 'Gabriel', 'last' => 'Mendoza', 'middle' => 'Navarro', 'v_type' => 'Sedan', 'v_name' => 'Hyundai Accent 1.4'],
            ['first' => 'Josephine', 'last' => 'Cruz', 'middle' => 'Gonzales', 'v_type' => 'Motorcycle', 'v_name' => 'Honda PCX 160'],
            ['first' => 'Marvin', 'last' => 'Bautista', 'middle' => 'Aquino', 'v_type' => 'SUV', 'v_name' => 'Toyota Fortuner 2.4 G'],
            ['first' => 'Raymond', 'last' => 'Castillo', 'middle' => 'Delos Reyes', 'v_type' => 'Motorcycle', 'v_name' => 'Yamaha Mio i125'],
            ['first' => 'Eduardo', 'last' => 'Villanueva', 'middle' => 'Tolentino', 'v_type' => 'Sedan', 'v_name' => 'Nissan Almera 1.0T'],
            ['first' => 'Christopher', 'last' => 'Ramos', 'middle' => 'Soriano', 'v_type' => 'Motorcycle', 'v_name' => 'Kawasaki Barako II'],
            ['first' => 'Fernando', 'last' => 'Poe', 'middle' => 'Jose', 'v_type' => 'SUV', 'v_name' => 'Nissan Terra 2.5'],
            ['first' => 'Paolo', 'last' => 'Contis', 'middle' => 'Manuel', 'v_type' => 'Motorcycle', 'v_name' => 'Honda Beat 110i'],
            ['first' => 'Richard', 'last' => 'Gomez', 'middle' => 'Frank', 'v_type' => 'Van', 'v_name' => 'Nissan Urvan NV350'],
            ['first' => 'Dingdong', 'last' => 'Dantes', 'middle' => 'Gonzalez', 'v_type' => 'Motorcycle', 'v_name' => 'Vespa Primavera 150'],
            ['first' => 'Dennis', 'last' => 'Trillo', 'middle' => 'Ho', 'v_type' => 'Sedan', 'v_name' => 'Toyota Corolla Altis'],
            ['first' => 'Coco', 'last' => 'Martin', 'middle' => 'Nacianceno', 'v_type' => 'Motorcycle', 'v_name' => 'Honda X-ADV 750'],
            ['first' => 'Gerald', 'last' => 'Anderson', 'middle' => 'Randolph', 'v_type' => 'SUV', 'v_name' => 'Ford Everest 2.0 Bi-Turbo'],
            ['first' => 'Piolo', 'last' => 'Pascual', 'middle' => 'Jose', 'v_type' => 'Motorcycle', 'v_name' => 'Yamaha XMAX 300'],
            ['first' => 'Jericho', 'last' => 'Rosales', 'middle' => 'Vibar', 'v_type' => 'Sedan', 'v_name' => 'Mazda 3 2.0'],
            ['first' => 'JM', 'last' => 'De Guzman', 'middle' => 'Gob', 'v_type' => 'Motorcycle', 'v_name' => 'Royal Enfield Meteor 350'],
            ['first' => 'Carlo', 'last' => 'Aquino', 'middle' => 'Jose', 'v_type' => 'SUV', 'v_name' => 'Isuzu mu-X 3.0'],
            ['first' => 'Joshua', 'last' => 'Garcia', 'middle' => 'Espineli', 'v_type' => 'Motorcycle', 'v_name' => 'Kymco Like 150i'],
            ['first' => 'Daniel', 'last' => 'Padilla', 'middle' => 'Ford', 'v_type' => 'Sedan', 'v_name' => 'Honda City RS Turbo'],
            ['first' => 'Enrique', 'last' => 'Gil', 'middle' => 'Mari', 'v_type' => 'Motorcycle', 'v_name' => 'BMW C 400 GT'],
            ['first' => 'Alden', 'last' => 'Richards', 'middle' => 'Faulkerson', 'v_type' => 'Van', 'v_name' => 'Toyota Alphard 3.5'],
            ['first' => 'James', 'last' => 'Reid', 'middle' => 'Robert', 'v_type' => 'Motorcycle', 'v_name' => 'Ducati Scrambler 800'],
            ['first' => 'Ruru', 'last' => 'Madrid', 'middle' => 'Ezekiel', 'v_type' => 'Sedan', 'v_name' => 'Kia Soluto 1.4'],
            ['first' => 'Donny', 'last' => 'Pangilinan', 'middle' => 'Laxa', 'v_type' => 'Motorcycle', 'v_name' => 'CFMoto 300NK'],
            ['first' => 'Seth', 'last' => 'Fedelin', 'middle' => 'Yancy', 'v_type' => 'SUV', 'v_name' => 'MG ZS 1.5 Alpha'],
            ['first' => 'Barbie', 'last' => 'Forteza', 'middle' => 'Imperial', 'v_type' => 'Motorcycle', 'v_name' => 'Yamaha Fazzio 125'],
        ];

        $driverUsers = collect();
        $drivers = collect();

        foreach ($filipinoDriverNames as $idx => $nameInfo) {
            $fullName = $nameInfo['first'] . ' ' . $nameInfo['last'];
            $email = strtolower($nameInfo['first'] . '.' . str_replace(' ', '', $nameInfo['last'])) . '@tripwise.app';

            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => bcrypt('password'),
                'role' => 'driver',
                'status' => 'active',
            ]);
            $driverUsers->push($user);

            $driver = Driver::create([
                'driver_id' => '#DRV-2026-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'first_name' => $nameInfo['first'],
                'middle_name' => $nameInfo['middle'],
                'last_name' => $nameInfo['last'],
                'photo' => null,
                'birth_date' => '1992-07-20',
                'gender' => ($idx % 3 === 0) ? 'Female' : 'Male',
                'civil_status' => 'Married',
                'address' => 'Metro Manila, Philippines',
                'contact_number' => '0917' . sprintf('%07d', $idx + 1000000),
                'email' => $email,
                'emergency_contact_person' => 'Family Contact',
                'emergency_contact_number' => '0918' . sprintf('%07d', $idx + 1000000),
                'date_hired' => '2024-01-15',
                'branch' => ['Central Branch', 'North Branch', 'South Branch', 'East Branch', 'West Branch'][$idx % 5],
                'vehicle_assignment' => $nameInfo['v_name'],
                'vehicle_type' => $nameInfo['v_type'],
                'route_assignment' => ['Central Route', 'North Route', 'South Route', 'East Route', 'West Route'][$idx % 5],
                'status' => ['active', 'active', 'active', 'review', 'active'][$idx % 5],
                'performance_score' => number_format(3.5 + ($idx % 15) * 0.1, 1),
                'trips_count' => 150 + ($idx * 45),
                'complaints_count' => $idx % 3,
                'username' => strtolower(str_replace(' ', '', $nameInfo['first'] . $nameInfo['last'])),
                'role' => 'Driver',
                'license_number' => 'N01-' . sprintf('%08d', $idx + 12345678),
                'license_expiration' => '2028-12-31',
            ]);
            $drivers->push($driver);
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
        }

        $juanUser = User::where('name', 'like', '%Juan Dela Cruz%')->first() ?? $driverUsers->first();

        \App\Models\CompetencyDevelopmentPlan::create([
            'driver_id' => $juanUser ? $juanUser->id : 1,
            'plan_name' => 'Advanced Defensive Driving & Safety Protocols',
            'description' => 'Comprehensive competency development plan for driver safety enhancement.',
            'assigned_competencies' => $competencies->random(min(3, $competencies->count()))->pluck('id')->toArray(),
            'assigned_trainings' => Training::inRandomOrder()->take(2)->pluck('id')->toArray(),
            'assigned_learning_modules' => Training::inRandomOrder()->take(2)->pluck('id')->toArray(),
            'coaching_sessions' => 3,
            'development_objectives' => 'Enhance defensive driving awareness and emergency handling.',
            'completion_percentage' => 85,
            'target_completion_date' => now()->addMonths(2),
            'hr_remarks' => 'Driver is performing well in practical exercises.',
            'status' => 'active',
            'created_by' => $adminUser->id,
            'updated_by' => $adminUser->id,
        ]);
    }
}