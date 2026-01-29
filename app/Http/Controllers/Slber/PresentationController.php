<?php

namespace App\Http\Controllers\Slber;

use App\Http\Controllers\Controller;
use App\Models\Keuzedeel;
use App\Models\Period;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    /**
     * Show presentation mode index
     */
    public function index(Request $request)
    {
        $periods = Period::orderBy('academic_year', 'desc')
            ->orderBy('period_number', 'desc')
            ->get();

        $selectedPeriodId = $request->get('period_id');
        $currentPeriod = $selectedPeriodId
            ? Period::find($selectedPeriodId)
            : Period::enrollmentOpen()->first() ?? $periods->first();

        $keuzedelen = collect();

        if ($currentPeriod) {
            $keuzedelen = Keuzedeel::active()
                ->whereHas('instances', function ($q) use ($currentPeriod) {
                    $q->where('period_id', $currentPeriod->id)->where('is_active', true);
                })
                ->with(['instances' => function ($q) use ($currentPeriod) {
                    $q->where('period_id', $currentPeriod->id)
                        ->where('is_active', true)
                        ->with('activeEnrollments');
                }])
                ->orderBy('name')
                ->get();
        }

        return view('slber.presentation.index', [
            'periods' => $periods,
            'currentPeriod' => $currentPeriod,
            'keuzedelen' => $keuzedelen,
        ]);
    }

    /**
     * Show fullscreen presentation
     */
    public function present(Request $request)
    {
        $periodId = $request->get('period_id');
        $period = $periodId ? Period::find($periodId) : Period::enrollmentOpen()->first();

        if (!$period) {
            return redirect()->route('slber.presentation.index')
                ->with('error', 'Geen periode geselecteerd.');
        }

        $keuzedelen = Keuzedeel::active()
            ->whereHas('instances', function ($q) use ($period) {
                $q->where('period_id', $period->id)->where('is_active', true);
            })
            ->with(['instances' => function ($q) use ($period) {
                $q->where('period_id', $period->id)
                    ->where('is_active', true)
                    ->with('activeEnrollments');
            }])
            ->orderBy('name')
            ->get();

        return view('slber.presentation.present', [
            'period' => $period,
            'keuzedelen' => $keuzedelen,
        ]);
    }

    /**
     * Show single keuzedeel slide
     */
    public function slide(Keuzedeel $keuzedeel)
    {
        $keuzedeel->load(['instances.activeEnrollments', 'programs']);

        return view('slber.presentation.slide', [
            'keuzedeel' => $keuzedeel,
        ]);
    }
}
