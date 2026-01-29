<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuzedeel;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeuzedeelController extends Controller
{
    /**
     * Display a listing of keuzedelen
     */
    public function index(Request $request)
    {
        $query = Keuzedeel::with('programs');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('active') && $request->get('active') !== '') {
            $query->where('is_active', $request->get('active') === '1');
        }

        $keuzedelen = $query->orderBy('name')->paginate(15);

        return view('admin.keuzedelen.index', [
            'keuzedelen' => $keuzedelen,
        ]);
    }

    /**
     * Show the form for creating a new keuzedeel
     */
    public function create()
    {
        $programs = Program::active()->orderBy('name')->get();

        return view('admin.keuzedelen.create', [
            'programs' => $programs,
        ]);
    }

    /**
     * Store a newly created keuzedeel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:keuzedelen,code',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_repeatable' => 'boolean',
            'is_active' => 'boolean',
            'max_students' => 'required|integer|min:1|max:100',
            'min_students' => 'required|integer|min:1|max:100',
            'credits' => 'nullable|integer|min:0',
            'programs' => 'required|array|min:1',
            'programs.*' => 'exists:programs,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_repeatable'] = $request->boolean('is_repeatable');
        $validated['is_active'] = $request->boolean('is_active', true);

        $keuzedeel = Keuzedeel::create($validated);
        $keuzedeel->programs()->sync($validated['programs']);

        return redirect()->route('admin.keuzedelen.index')
            ->with('success', 'Keuzedeel succesvol aangemaakt.');
    }

    /**
     * Display the specified keuzedeel
     */
    public function show(Keuzedeel $keuzedeel)
    {
        $keuzedeel->load(['programs', 'instances.period', 'instances.activeEnrollments.user']);

        return view('admin.keuzedelen.show', [
            'keuzedeel' => $keuzedeel,
        ]);
    }

    /**
     * Show the form for editing the specified keuzedeel
     */
    public function edit(Keuzedeel $keuzedeel)
    {
        $programs = Program::active()->orderBy('name')->get();
        $selectedPrograms = $keuzedeel->programs->pluck('id')->toArray();

        return view('admin.keuzedelen.edit', [
            'keuzedeel' => $keuzedeel,
            'programs' => $programs,
            'selectedPrograms' => $selectedPrograms,
        ]);
    }

    /**
     * Update the specified keuzedeel
     */
    public function update(Request $request, Keuzedeel $keuzedeel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:keuzedelen,code,' . $keuzedeel->id,
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'is_repeatable' => 'boolean',
            'is_active' => 'boolean',
            'max_students' => 'required|integer|min:1|max:100',
            'min_students' => 'required|integer|min:1|max:100',
            'credits' => 'nullable|integer|min:0',
            'programs' => 'required|array|min:1',
            'programs.*' => 'exists:programs,id',
        ]);

        $validated['is_repeatable'] = $request->boolean('is_repeatable');
        $validated['is_active'] = $request->boolean('is_active');

        $keuzedeel->update($validated);
        $keuzedeel->programs()->sync($validated['programs']);

        return redirect()->route('admin.keuzedelen.index')
            ->with('success', 'Keuzedeel succesvol bijgewerkt.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Keuzedeel $keuzedeel)
    {
        $keuzedeel->update(['is_active' => !$keuzedeel->is_active]);

        $status = $keuzedeel->is_active ? 'geactiveerd' : 'gedeactiveerd';
        return back()->with('success', "Keuzedeel {$status}.");
    }

    /**
     * Remove the specified keuzedeel
     */
    public function destroy(Keuzedeel $keuzedeel)
    {
        // Check if there are any enrollments
        $hasEnrollments = $keuzedeel->instances()
            ->whereHas('enrollments')
            ->exists();

        if ($hasEnrollments) {
            return back()->with('error', 'Dit keuzedeel kan niet worden verwijderd omdat er inschrijvingen zijn.');
        }

        $keuzedeel->delete();

        return redirect()->route('admin.keuzedelen.index')
            ->with('success', 'Keuzedeel succesvol verwijderd.');
    }
}
