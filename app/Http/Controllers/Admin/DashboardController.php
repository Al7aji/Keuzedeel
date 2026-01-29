<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Keuzedeel;
use App\Models\KeuzedeelInstance;
use App\Models\Period;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_students' => User::students()->count(),
            'total_keuzedelen' => Keuzedeel::count(),
            'active_keuzedelen' => Keuzedeel::active()->count(),
            'total_enrollments' => Enrollment::enrolled()->count(),
            'periods_with_open_enrollment' => Period::enrollmentOpen()->count(),
        ];

        // Current period stats
        $currentPeriod = Period::current()->first();
        $currentPeriodStats = null;

        if ($currentPeriod) {
            $instances = KeuzedeelInstance::with(['keuzedeel', 'activeEnrollments'])
                ->where('period_id', $currentPeriod->id)
                ->get();

            $currentPeriodStats = [
                'period' => $currentPeriod,
                'instances' => $instances,
                'total_enrollments' => $instances->sum(fn($i) => $i->activeEnrollments->count()),
                'instances_below_minimum' => $instances->filter(fn($i) => !$i->hasMinimumStudents())->count(),
                'instances_full' => $instances->filter(fn($i) => $i->isFull())->count(),
            ];
        }

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'keuzedeelInstance.keuzedeel'])
            ->orderBy('enrolled_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'currentPeriodStats' => $currentPeriodStats,
            'recentEnrollments' => $recentEnrollments,
        ]);
    }
}
