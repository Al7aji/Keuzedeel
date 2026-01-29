<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of programs
     */
    public function index()
    {
        $programs = Program::withCount('users')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.programs.index', [
            'programs' => $programs,
        ]);
    }

    /**
     * Show the form for creating a new program
     */
    public function create()
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created program
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:programs,code',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Program::create($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Opleiding succesvol aangemaakt.');
    }

    /**
     * Show the form for editing the specified program
     */
    public function edit(Program $program)
    {
        return view('admin.programs.edit', [
            'program' => $program,
        ]);
    }

    /**
     * Update the specified program
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:programs,code,' . $program->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $program->update($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Opleiding succesvol bijgewerkt.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(Program $program)
    {
        $program->update(['is_active' => !$program->is_active]);

        $status = $program->is_active ? 'geactiveerd' : 'gedeactiveerd';
        return back()->with('success', "Opleiding {$status}.");
    }

    /**
     * Remove the specified program
     */
    public function destroy(Program $program)
    {
        if ($program->users()->count() > 0) {
            return back()->with('error', 'Opleiding kan niet worden verwijderd omdat er studenten aan gekoppeld zijn.');
        }

        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Opleiding succesvol verwijderd.');
    }
}
