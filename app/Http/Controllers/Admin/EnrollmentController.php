<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\KeuzedeelInstance;
use App\Models\Period;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['user', 'keuzedeelInstance.keuzedeel', 'keuzedeelInstance.period']);

        // Filter by period
        if ($request->has('period_id') && $request->period_id) {
            $query->whereHas('keuzedeelInstance', function ($q) use ($request) {
                $q->where('period_id', $request->period_id);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by keuzedeel
        if ($request->has('keuzedeel_id') && $request->keuzedeel_id) {
            $query->whereHas('keuzedeelInstance', function ($q) use ($request) {
                $q->where('keuzedeel_id', $request->keuzedeel_id);
            });
        }

        // Search by student name or number
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->orderBy('enrolled_at', 'desc')->paginate(20);
        $periods = Period::orderBy('academic_year', 'desc')->orderBy('period_number', 'desc')->get();

        return view('admin.enrollments.index', [
            'enrollments' => $enrollments,
            'periods' => $periods,
        ]);
    }

    /**
     * Show enrollment details
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user.program', 'keuzedeelInstance.keuzedeel', 'keuzedeelInstance.period']);

        return view('admin.enrollments.show', [
            'enrollment' => $enrollment,
        ]);
    }

    /**
     * Update enrollment status
     */
    public function updateStatus(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:enrolled,completed,cancelled',
        ]);

        $enrollment->status = $validated['status'];

        if ($validated['status'] === 'completed') {
            $enrollment->completed_at = now();
        } elseif ($validated['status'] === 'cancelled') {
            $enrollment->cancelled_at = now();
        }

        $enrollment->save();

        return back()->with('success', 'Status bijgewerkt.');
    }

    /**
     * Bulk update enrollments to completed
     */
    public function bulkComplete(Request $request)
    {
        $validated = $request->validate([
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
        ]);

        Enrollment::whereIn('id', $validated['enrollment_ids'])
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

        return back()->with('success', count($validated['enrollment_ids']) . ' inschrijvingen als afgerond gemarkeerd.');
    }

    /**
     * Export enrollments for a period
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:periods,id',
        ]);

        $enrollments = Enrollment::with(['user.program', 'keuzedeelInstance.keuzedeel'])
            ->whereHas('keuzedeelInstance', function ($q) use ($validated) {
                $q->where('period_id', $validated['period_id']);
            })
            ->whereIn('status', ['enrolled', 'completed'])
            ->get();

        $period = Period::find($validated['period_id']);

        $filename = "inschrijvingen-{$period->academic_year}-periode-{$period->period_number}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($enrollments) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($file, [
                'Studentnummer',
                'Naam',
                'Email',
                'Opleiding',
                'Keuzedeel',
                'Status',
                'Ingeschreven op',
            ], ';');

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->user->student_number ?? '-',
                    $enrollment->user->name,
                    $enrollment->user->email,
                    $enrollment->user->program->name ?? '-',
                    $enrollment->keuzedeelInstance->keuzedeel->name,
                    $enrollment->status,
                    $enrollment->enrolled_at->format('d-m-Y H:i'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
