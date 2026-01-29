<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Keuzedeel;
use App\Models\KeuzedeelInstance;
use App\Models\Period;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Programs
        $programs = [
            ['name' => 'Software Development', 'code' => 'SD'],
            ['name' => 'ICT & Infrastructuur', 'code' => 'ICT-I'],
            ['name' => 'Data Science', 'code' => 'DS'],
            ['name' => 'Cyber Security', 'code' => 'CS'],
        ];

        foreach ($programs as $programData) {
            Program::create($programData);
        }

        $sdProgram = Program::where('code', 'SD')->first();
        $allPrograms = Program::all();

        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.nl',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create SLB-er User
        User::create([
            'name' => 'SLB Teacher',
            'email' => 'slber@school.nl',
            'password' => Hash::make('password'),
            'role' => 'slber',
        ]);

        // Create Student Users
        $students = [
            ['name' => 'Jan de Vries', 'email' => 'jan@student.nl', 'student_number' => 'S001', 'program_id' => $sdProgram->id],
            ['name' => 'Lisa Jansen', 'email' => 'lisa@student.nl', 'student_number' => 'S002', 'program_id' => $sdProgram->id],
            ['name' => 'Peter Bakker', 'email' => 'peter@student.nl', 'student_number' => 'S003', 'program_id' => $sdProgram->id],
            ['name' => 'Emma Visser', 'email' => 'emma@student.nl', 'student_number' => 'S004', 'program_id' => $sdProgram->id],
            ['name' => 'Tom Smit', 'email' => 'tom@student.nl', 'student_number' => 'S005', 'program_id' => $sdProgram->id],
        ];

        foreach ($students as $studentData) {
            User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'student_number' => $studentData['student_number'],
                'program_id' => $studentData['program_id'],
            ]);
        }

        // Create Periods
        $currentYear = now()->year;
        $currentMonth = now()->month;
        
        // Determine academic year
        $academicYear = $currentMonth >= 9 ? "{$currentYear}-" . ($currentYear + 1) : ($currentYear - 1) . "-{$currentYear}";
        
        // Calculate which academic year we should use for periods
        // If we're in Sep-Dec, use current year for start; if Jan-Aug, use previous year
        $startYear = $currentMonth >= 9 ? $currentYear : $currentYear - 1;
        $endYear = $startYear + 1;

        $periods = [
            ['name' => 'Periode 1', 'academic_year' => $academicYear, 'period_number' => 1, 'start_date' => "{$startYear}-09-01", 'end_date' => "{$startYear}-11-01", 'enrollment_open' => false],
            ['name' => 'Periode 2', 'academic_year' => $academicYear, 'period_number' => 2, 'start_date' => "{$startYear}-11-01", 'end_date' => "{$endYear}-01-31", 'enrollment_open' => true],
            ['name' => 'Periode 3', 'academic_year' => $academicYear, 'period_number' => 3, 'start_date' => "{$endYear}-02-01", 'end_date' => "{$endYear}-04-01", 'enrollment_open' => false],
            ['name' => 'Periode 4', 'academic_year' => $academicYear, 'period_number' => 4, 'start_date' => "{$endYear}-04-01", 'end_date' => "{$endYear}-07-01", 'enrollment_open' => false],
        ];

        foreach ($periods as $periodData) {
            Period::create($periodData);
        }

        $period2 = Period::where('period_number', 2)->first();

        // Create Keuzedelen
        $keuzedelen = [
            [
                'name' => 'Verdieping Software',
                'code' => 'K0123',
                'slug' => 'verdieping-software',
                'short_description' => 'Verdiep je kennis in softwareontwikkeling met geavanceerde technieken.',
                'content' => '<h3>Wat leer je?</h3><p>In dit keuzedeel leer je geavanceerde software development technieken...</p>',
                'is_repeatable' => true,
                'max_students' => 30,
                'min_students' => 15,
                'credits' => 10,
            ],
            [
                'name' => 'Cloud Computing',
                'code' => 'K0456',
                'slug' => 'cloud-computing',
                'short_description' => 'Leer werken met cloud platforms zoals AWS, Azure en Google Cloud.',
                'content' => '<h3>Inhoud</h3><p>Cloud computing is de toekomst van IT infrastructuur...</p>',
                'is_repeatable' => false,
                'max_students' => 25,
                'min_students' => 10,
                'credits' => 8,
            ],
            [
                'name' => 'Machine Learning Basics',
                'code' => 'K0789',
                'slug' => 'machine-learning-basics',
                'short_description' => 'Introductie tot machine learning en kunstmatige intelligentie.',
                'content' => '<h3>Over dit keuzedeel</h3><p>Ontdek de wereld van AI en machine learning...</p>',
                'is_repeatable' => false,
                'max_students' => 20,
                'min_students' => 10,
                'credits' => 12,
            ],
            [
                'name' => 'DevOps & CI/CD',
                'code' => 'K1011',
                'slug' => 'devops-cicd',
                'short_description' => 'Leer de principes van DevOps en continuous integration/deployment.',
                'content' => '<h3>DevOps Cultuur</h3><p>DevOps is meer dan tools, het is een cultuur...</p>',
                'is_repeatable' => false,
                'max_students' => 25,
                'min_students' => 12,
                'credits' => 8,
            ],
            [
                'name' => 'Mobile App Development',
                'code' => 'K1213',
                'slug' => 'mobile-app-development',
                'short_description' => 'Ontwikkel native en cross-platform mobiele applicaties.',
                'content' => '<h3>Mobiel ontwikkelen</h3><p>Leer apps bouwen voor iOS en Android...</p>',
                'is_repeatable' => true,
                'max_students' => 30,
                'min_students' => 15,
                'credits' => 10,
            ],
        ];

        foreach ($keuzedelen as $keuzedeelData) {
            $keuzedeel = Keuzedeel::create($keuzedeelData);
            // Attach to all programs
            $keuzedeel->programs()->attach($allPrograms->pluck('id'));

            // Create instance for period 2
            KeuzedeelInstance::create([
                'keuzedeel_id' => $keuzedeel->id,
                'period_id' => $period2->id,
                'instance_number' => 1,
                'is_active' => true,
            ]);
        }

        // Create some enrollments
        $verdiepingSoftware = KeuzedeelInstance::whereHas('keuzedeel', fn($q) => $q->where('code', 'K0123'))->first();

        if ($verdiepingSoftware) {
            $jan = User::where('email', 'jan@student.nl')->first();
            $lisa = User::where('email', 'lisa@student.nl')->first();

            if ($jan) {
                Enrollment::create([
                    'user_id' => $jan->id,
                    'keuzedeel_instance_id' => $verdiepingSoftware->id,
                    'status' => 'enrolled',
                    'enrolled_at' => now()->subDays(5),
                ]);
            }

            if ($lisa) {
                Enrollment::create([
                    'user_id' => $lisa->id,
                    'keuzedeel_instance_id' => $verdiepingSoftware->id,
                    'status' => 'enrolled',
                    'enrolled_at' => now()->subDays(3),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Test accounts:');
        $this->command->info('Admin: admin@school.nl / password');
        $this->command->info('SLB-er: slber@school.nl / password');
        $this->command->info('Student: jan@student.nl / password');
    }
}
