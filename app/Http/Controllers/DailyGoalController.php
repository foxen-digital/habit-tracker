<?php

namespace App\Http\Controllers;

use App\Models\DailyGoal;
use App\Models\DailyGoalCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DailyGoalController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'emoji' => 'nullable|string|max:10',
        ]);

        $maxOrder = DailyGoal::max('sort_order') ?? 0;

        DailyGoal::create([
            'name' => $validated['name'],
            'emoji' => $validated['emoji'] ?? '✅',
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect('/')->with('success', 'Goal created!');
    }

    public function toggleCompletion(Request $request, DailyGoal $goal): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'completed' => 'required|boolean',
        ]);

        DailyGoalCompletion::updateOrCreate(
            [
                'daily_goal_id' => $goal->id,
                'date' => $validated['date'],
            ],
            ['completed' => $validated['completed']]
        );

        $message = $validated['completed'] ? 'Goal marked as complete!' : 'Goal unmarked.';

        return redirect('/')->with('success', $message);
    }

    public function update(Request $request, DailyGoal $goal): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'emoji' => 'sometimes|nullable|string|max:10',
            'is_active' => 'sometimes|boolean',
        ]);

        $goal->update($validated);

        return redirect('/')->with('success', 'Goal updated!');
    }

    public function destroy(DailyGoal $goal): RedirectResponse
    {
        $goal->delete();

        return redirect('/')->with('success', 'Goal deleted!');
    }
}
