<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuzedeel;
use App\Models\KeuzedeelInstance;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of periods
     */
    public function index()
    {
        $periods = Period::withCount('keuzedeelInstances')
            ->orderBy('academic_year', 'desc')
            ->orderBy('period_number', 'desc')
            ->paginate(15);

        return view('admin.periods.index', [
            'periods' => $periods,
        ]);
    }

    /**
     * Show the form for creating a new period
     */
    public function create()
    {
        return view('admin.periods.create');
    }

    /**
     * Store a newly created period
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'period_number' => 'required|integer|min:1|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start' => 'nullable|date',
            'enrollment_end' => 'nullable|date|after:enrollment_start',
        ]);

        $validated['enrollment_open'] = false;

        Period::create($validated);

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode succesvol aangemaakt.');
    }

    /**
     * Display the specified period
     */
    public function show(Period $period)
    {
        $period->load(['keuzedeelInstances.keuzedeel', 'keuzedeelInstances.activeEnrollments.user']);

        return view('admin.periods.show', [
            'period' => $period,
        ]);
    }

    /**
     * Show the form for editing the specified period
     */
    public function edit(Period $period)
    {
        return view('admin.periods.edit', [
            'period' => $period,
        ]);
    }

    /**
     * Update the specified period
     */
    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'period_number' => 'required|integer|min:1|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'enrollment_start' => 'nullable|date',
            'enrollment_end' => 'nullable|date|after:enrollment_start',
        ]);

        $period->update($validated);

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode succesvol bijgewerkt.');
    }

    /**
     * Toggle enrollment open/closed
     */
    public function toggleEnrollment(Period $period)
    {
        $period->update(['enrollment_open' => !$period->enrollment_open]);

        $status = $period->enrollment_open ? 'geopend' : 'gesloten';
        return back()->with('success', "Inschrijving voor periode {$status}.");
    }

    /**
     * Manage keuzedeel instances for a period
     */
    public function manageInstances(Period $period)
    {
        $instances = $period->keuzedeelInstances()
            ->with(['keuzedeel', 'activeEnrollments'])
            ->get();

        $availableKeuzedelen = Keuzedeel::active()
            ->whereDoesntHave('instances', function ($q) use ($period) {
                $q->where('period_id', $period->id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.periods.instances', [
            'period' => $period,
            'instances' => $instances,
            'availableKeuzedelen' => $availableKeuzedelen,
        ]);
    }

    /**
     * Add a keuzedeel instance to a period
     */
    public function addInstance(Request $request, Period $period)
    {
        $validated = $request->validate([
            'keuzedeel_id' => 'required|exists:keuzedelen,id',
            'instance_number' => 'nullable|integer|min:1',
        ]);

        $instanceNumber = $validated['instance_number'] ?? 1;

        // Check if instance already exists
        $exists = KeuzedeelInstance::where('period_id', $period->id)
            ->where('keuzedeel_id', $validated['keuzedeel_id'])
            ->where('instance_number', $instanceNumber)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Deze keuzedeel instance bestaat al in deze periode.');
        }

        KeuzedeelInstance::create([
            'keuzedeel_id' => $validated['keuzedeel_id'],
            'period_id' => $period->id,
            'instance_number' => $instanceNumber,
            'is_active' => true,
        ]);

        return back()->with('success', 'Keuzedeel toegevoegd aan periode.');
    }

    /**
     * Remove a keuzedeel instance from a period
     */
    public function removeInstance(Period $period, KeuzedeelInstance $instance)
    {
        if ($instance->activeEnrollments()->count() > 0) {
            return back()->with('error', 'Kan instance niet verwijderen omdat er inschrijvingen zijn.');
        }

        $instance->delete();

        return back()->with('success', 'Keuzedeel verwijderd uit periode.');
    }

    /**
     * Remove the specified period
     */
    public function destroy(Period $period)
    {
        $hasEnrollments = $period->keuzedeelInstances()
            ->whereHas('enrollments')
            ->exists();

        if ($hasEnrollments) {
            return back()->with('error', 'Periode kan niet worden verwijderd omdat er inschrijvingen zijn.');
        }

        $period->delete();

        return redirect()->route('admin.periods.index')
            ->with('success', 'Periode succesvol verwijderd.');
    }
}
