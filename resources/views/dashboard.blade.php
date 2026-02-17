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
        <header class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-emerald-400">Danny's Habit Tracker</h1>
                <p class="text-gray-400 mt-1">{{ now()->format('l, F jS, Y') }}</p>
            </div>
            <button onclick="toggleEntryForms()" class="bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-lg font-medium transition-colors">
                + Add Entry
            </button>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-4 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Entry Forms (Hidden by default) -->
        <div id="entryForms" class="hidden mb-8 bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h2 class="text-xl font-semibold mb-4">Quick Entry</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Weight Form -->
                <form action="{{ route('weight.store') }}" method="POST" class="bg-gray-700/50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-medium text-emerald-400 mb-3">⚖️ Weight</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Weight (kg)</label>
                            <input type="number" name="weight_kg" step="0.1" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Notes (optional)</label>
                            <input type="text" name="notes" placeholder="e.g., Felt bloated today"
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-emerald-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-2 rounded font-medium transition-colors">
                            Save Weight
                        </button>
                    </div>
                </form>

                <!-- Walk Form -->
                <form action="{{ route('walk.store') }}" method="POST" class="bg-gray-700/50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-medium text-blue-400 mb-3">🚶 Walk</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Distance (miles)</label>
                            <input type="number" name="distance_miles" step="0.1" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Steps (optional)</label>
                            <input type="number" name="steps"
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Notes (optional)</label>
                            <input type="text" name="notes" placeholder="e.g., Morning walk in the park"
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-blue-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-2 rounded font-medium transition-colors">
                            Save Walk
                        </button>
                    </div>
                </form>

                <!-- Water Form -->
                <form action="{{ route('water.store') }}" method="POST" class="bg-gray-700/50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-medium text-cyan-400 mb-3">💧 Water</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Glasses of water</label>
                            <input type="number" name="glasses" min="0" max="20" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-cyan-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-cyan-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 py-2 rounded font-medium transition-colors">
                            Save Water
                        </button>
                    </div>
                </form>

                <!-- Mood Form -->
                <form action="{{ route('mood.store') }}" method="POST" class="bg-gray-700/50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-medium text-purple-400 mb-3">😊 Mood</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">How are you feeling?</label>
                            <select name="mood" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-purple-500 focus:outline-none">
                                <option value="">Select mood...</option>
                                <option value="great">🌟 Great</option>
                                <option value="good">😊 Good</option>
                                <option value="okay">😐 Okay</option>
                                <option value="bad">😔 Bad</option>
                                <option value="terrible">😫 Terrible</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Energy level (1-10)</label>
                            <input type="range" name="energy_level" min="1" max="10" value="5"
                                class="w-full mt-1 accent-purple-500" oninput="this.nextElementSibling.textContent = this.value">
                            <span class="text-purple-400 font-medium">5</span>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Sleep quality (1-10, optional)</label>
                            <input type="range" name="sleep_quality" min="1" max="10" value="5"
                                class="w-full mt-1 accent-purple-500" oninput="this.nextElementSibling.textContent = this.value">
                            <span class="text-purple-400 font-medium">5</span>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Date</label>
                            <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Notes (optional)</label>
                            <input type="text" name="notes" placeholder="e.g., Good sleep, productive day"
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-purple-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 py-2 rounded font-medium transition-colors">
                            Save Mood
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
        function toggleEntryForms() {
            const forms = document.getElementById('entryForms');
            forms.classList.toggle('hidden');
        }

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
