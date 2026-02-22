<?php

namespace App\Http\Controllers;

use App\Models\GlucoseEntry;
use App\Models\MoodEntry;
use App\Models\WalkEntry;
use App\Models\WaterEntry;
use App\Models\WeightEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function storeWeight(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:50|max:300',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255',
        ]);

        WeightEntry::create($validated);

        return redirect('/')->with('success', 'Weight entry saved!');
    }

    public function storeWalk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'distance_miles' => 'required|numeric|min:0|max:50',
            'steps' => 'nullable|integer|min:0|max:100000',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:255',
        ]);

        WalkEntry::create($validated);

        return redirect('/')->with('success', 'Walk entry saved!');
    }

    public function storeWater(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'glasses' => 'required|integer|min:0|max:20',
            'date' => 'required|date|before_or_equal:today',
        ]);

        // Update or create - one water entry per day
        WaterEntry::updateOrCreate(
            ['date' => $validated['date']],
            ['glasses' => $validated['glasses']]
        );

        return redirect('/')->with('success', 'Water entry saved!');
    }

    public function storeMood(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mood' => 'required|in:great,good,okay,bad,terrible',
            'energy_level' => 'required|integer|min:1|max:10',
            'sleep_quality' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string|max:255',
            'date' => 'required|date|before_or_equal:today',
        ]);

        // Update or create - one mood entry per day
        MoodEntry::updateOrCreate(
            ['date' => $validated['date']],
            $validated
        );

        return redirect('/')->with('success', 'Mood entry saved!');
    }

    public function storeGlucose(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'glucose_mmol_l' => 'required|numeric|min:1|max:30',
            'reading_type' => 'required|in:'.implode(',', array_keys(GlucoseEntry::READING_TYPES)),
            'measured_at' => 'required|date|before_or_equal:now',
            'notes' => 'nullable|string|max:500',
        ]);

        GlucoseEntry::create($validated);

        return redirect('/')->with('success', 'Glucose entry saved!');
    }
}
