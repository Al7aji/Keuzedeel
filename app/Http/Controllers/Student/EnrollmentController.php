<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\KeuzedeelInstance;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    /**
     * Display student's enrollments
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $enrollments = $user->enrollments()
            ->with(['keuzedeelInstance.keuzedeel', 'keuzedeelInstance.period'])
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return view('student.enrollments.index', [
            'enrollments' => $enrollments,
        ]);
    }

    /**
     * Enroll in a keuzedeel instance
     */
    public function store(Request $request)
    {
        $request->validate([
            'keuzedeel_instance_id' => 'required|exists:keuzedeel_instances,id',
        ]);

        $user = $request->user();
        $instance = KeuzedeelInstance::with(['keuzedeel.programs', 'period'])->findOrFail($request->keuzedeel_instance_id);

        $result = $this->enrollmentService->enroll($user, $instance);

        if ($result['success']) {
            return redirect()->route('student.enrollments.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }

    /**
     * Cancel an enrollment
     */
    public function destroy(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();

        $result = $this->enrollmentService->cancel($enrollment, $user);

        if ($result['success']) {
            return redirect()->route('student.enrollments.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
