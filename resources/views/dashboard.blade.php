<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danny's Habit Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-emerald-400">Danny's Habit Tracker</h1>
            <p class="text-gray-400 mt-1">{{ now()->format('l, F jS, Y') }}</p>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Weight Progress -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Weight Goal</span>
                    <span class="text-emerald-400 text-2xl">⚖️</span>
                </div>
                @if($weightProgress['current'])
                    <div class="text-3xl font-bold text-white">{{ $weightProgress['lost'] }}kg</div>
                    <div class="text-sm text-gray-400">of 25kg goal lost</div>
                    <div class="mt-3 bg-gray-700 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $weightProgress['progress_percent'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">{{ $weightProgress['progress_percent'] }}% complete</div>
                @else
                    <div class="text-gray-500">No data yet</div>
                @endif
            </div>

            <!-- Walking Stats -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Weekly Walking</span>
                    <span class="text-blue-400 text-2xl">🚶</span>
                </div>
                <div class="text-3xl font-bold text-white">{{ $walkStats['total_miles'] ?? 0 }}mi</div>
                <div class="text-sm text-gray-400">avg {{ $walkStats['average_miles'] ?? 0 }}mi/day</div>
                <div class="mt-3 flex gap-1">
                    @for($i = 1; $i <= 7; $i++)
                        <div class="w-full h-2 {{ $i <= $walkStats['average_miles'] ? 'bg-blue-500' : 'bg-gray-700' }} rounded"></div>
                    @endfor
                </div>
                <div class="text-xs text-gray-500 mt-1">Goal: 3mi/day</div>
            </div>

            <!-- Water Intake -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Water Today</span>
                    <span class="text-cyan-400 text-2xl">💧</span>
                </div>
                <div class="text-3xl font-bold text-white">{{ $waterToday }}</div>
                <div class="text-sm text-gray-400">glasses ({{ $waterToday * 250 }}ml)</div>
                <div class="mt-3 flex gap-1">
                    @for($i = 1; $i <= 8; $i++)
                        <div class="w-4 h-6 {{ $i <= $waterToday ? 'bg-cyan-500' : 'bg-gray-700' }} rounded"></div>
                    @endfor
                </div>
                <div class="text-xs text-gray-500 mt-1">Goal: 8 glasses</div>
            </div>

            <!-- Mood Trend -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Weekly Mood</span>
                    <span class="text-purple-400 text-2xl">😊</span>
                </div>
                <div class="text-3xl font-bold text-white">{{ $moodTrend['average_mood'] }}/5</div>
                <div class="text-sm text-gray-400">avg mood score</div>
                <div class="mt-3 text-xs text-gray-500">
                    Energy: {{ $moodTrend['average_energy'] }}/10 | Sleep: {{ $moodTrend['average_sleep'] }}/10
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Weight Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Weight Trend (Last 7 Days)</h3>
                <canvas id="weightChart" height="200"></canvas>
            </div>

            <!-- Walking Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Walking Distance (Last 7 Days)</h3>
                <canvas id="walkChart" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Entries -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Recent Weights</h3>
                <div class="space-y-2">
                    @forelse($recentWeights as $entry)
                        <div class="flex justify-between items-center py-2 border-b border-gray-700">
                            <span class="text-gray-400">{{ $entry->date->format('M j') }}</span>
                            <span class="font-semibold">{{ $entry->weight_kg }}kg</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No entries yet</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Recent Walks</h3>
                <div class="space-y-2">
                    @forelse($recentWalks as $entry)
                        <div class="flex justify-between items-center py-2 border-b border-gray-700">
                            <span class="text-gray-400">{{ $entry->date->format('M j') }}</span>
                            <span class="font-semibold">{{ $entry->distance_miles }}mi {{ $entry->steps ? "({$entry->steps} steps)" : '' }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No entries yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 text-center text-gray-500 text-sm">
            Built with Laravel • Habit Tracker MVP
        </footer>
    </div>

    <script>
        // Weight Chart
        const weightCtx = document.getElementById('weightChart').getContext('2d');
        const weightLabels = @json($recentWeights->pluck('date')->map(fn($d) => $d->format('M j'))->reverse()->values());
        const weightData = @json($recentWeights->pluck('weight_kg')->reverse()->values());
        
        new Chart(weightCtx, {
            type: 'line',
            data: {
                labels: weightLabels,
                datasets: [{
                    label: 'Weight (kg)',
                    data: weightData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#374151' }, ticks: { color: '#9ca3af' } },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            }
        });

        // Walk Chart
        const walkCtx = document.getElementById('walkChart').getContext('2d');
        const walkLabels = @json($recentWalks->pluck('date')->map(fn($d) => $d->format('M j'))->reverse()->values());
        const walkData = @json($recentWalks->pluck('distance_miles')->reverse()->values());
        
        new Chart(walkCtx, {
            type: 'bar',
            data: {
                labels: walkLabels,
                datasets: [{
                    label: 'Distance (mi)',
                    data: walkData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#374151' }, ticks: { color: '#9ca3af' }, suggestedMax: 4 },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            }
        });
    </script>
</body>
</html>
