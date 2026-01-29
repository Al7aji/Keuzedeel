<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Keuzedeel;
use App\Models\KeuzedeelInstance;
use App\Models\Period;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class KeuzedeelController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * Display a listing of available keuzedelen
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $programId = $user->program_id;

        // Get current or selected period
        $periods = Period::orderBy('academic_year', 'desc')
            ->orderBy('period_number', 'desc')
            ->get();

        $selectedPeriodId = $request->get('period_id');
        $currentPeriod = $selectedPeriodId
            ? Period::find($selectedPeriodId)
            : Period::current()->first() ?? $periods->first();

        if (!$currentPeriod) {
            return view('student.keuzedelen.index', [
                'instances' => collect(),
                'periods' => $periods,
                'currentPeriod' => null,
                'completedIds' => [],
            ]);
        }

        // Get keuzedeel instances for this period that are available to the student's program
        $instances = KeuzedeelInstance::with(['keuzedeel', 'period', 'activeEnrollments'])
            ->where('period_id', $currentPeriod->id)
            ->where('is_active', true)
            ->withActiveKeuzedeel()
            ->whereHas('keuzedeel.programs', function ($q) use ($programId) {
                $q->where('programs.id', $programId);
            })
            ->get()
            ->map(function ($instance) use ($user) {
                $instance->can_enroll_result = $this->enrollmentService->canEnroll($user, $instance);
                return $instance;
            });

        $completedIds = $user->getCompletedKeuzedeelIds();

        return view('student.keuzedelen.index', [
            'instances' => $instances,
            'periods' => $periods,
            'currentPeriod' => $currentPeriod,
            'completedIds' => $completedIds,
        ]);
    }

    /**
     * Display the specified keuzedeel
     */
    public function show(Request $request, Keuzedeel $keuzedeel)
    {
        $user = $request->user();

        // Check if keuzedeel is available for student's program
        if (!$keuzedeel->programs->contains($user->program_id)) {
            abort(403, 'Dit keuzedeel is niet beschikbaar voor jouw opleiding.');
        }

        // Get instances with enrollment info
        $instances = $keuzedeel->instances()
            ->with(['period', 'activeEnrollments'])
            ->whereHas('period', function ($q) {
                $q->where('enrollment_open', true);
            })
            ->where('is_active', true)
            ->get()
            ->map(function ($instance) use ($user) {
                $instance->can_enroll_result = $this->enrollmentService->canEnroll($user, $instance);
                return $instance;
            });

        $hasCompleted = $user->hasCompletedKeuzedeel($keuzedeel->id);

        return view('student.keuzedelen.show', [
            'keuzedeel' => $keuzedeel,
            'instances' => $instances,
            'hasCompleted' => $hasCompleted,
        ]);
    }
}
