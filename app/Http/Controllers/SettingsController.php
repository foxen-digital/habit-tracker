<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the user's settings.
     */
    public function index(Request $request): View
    {
        $settings = $request->user()->getSettings();

        return view('settings.index', compact('settings'));
    }

    /**
     * Update the user's settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weight_goal_kg' => 'required|numeric|min:1|max:200',
            'daily_walk_target_miles' => 'required|numeric|min:0|max:50',
            'daily_water_target_glasses' => 'required|integer|min:1|max:20',
            'weight_unit' => 'required|in:kg,lbs',
            'distance_unit' => 'required|in:miles,km',
        ]);

        $settings = $request->user()->getSettings();
        $settings->update($validated);

        return redirect()->route('settings.index')->with('success', 'Settings updated!');
    }
}
