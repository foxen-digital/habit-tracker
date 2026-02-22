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

                <!-- Glucose Form -->
                <form action="{{ route('glucose.store') }}" method="POST" class="bg-gray-700/50 rounded-lg p-4">
                    @csrf
                    <h3 class="font-medium text-red-400 mb-3">🩸 Glucose</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-400">Glucose (mmol/L)</label>
                            <input type="number" name="glucose_mmol_l" step="0.1" min="1" max="30" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-red-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Reading Type</label>
                            <select name="reading_type" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-red-500 focus:outline-none">
                                <option value="">Select type...</option>
                                <option value="fasting">Fasting</option>
                                <option value="pre_meal">Pre-meal</option>
                                <option value="post_meal">Post-meal</option>
                                <option value="bedtime">Bedtime</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Date & Time</label>
                            <input type="datetime-local" name="measured_at" value="{{ now()->format('Y-m-d\TH:i') }}" required
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-red-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-sm text-gray-400">Notes (optional)</label>
                            <input type="text" name="notes" placeholder="e.g., After breakfast"
                                class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-red-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded font-medium transition-colors">
                            Save Glucose
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
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

            <!-- Glucose Stats -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Weekly Glucose</span>
                    <span class="text-red-400 text-2xl">🩸</span>
                </div>
                @if($glucoseStats['average'])
                    <div class="text-3xl font-bold text-white">{{ $glucoseStats['average'] }}</div>
                    <div class="text-sm text-gray-400">mmol/L avg ({{ $glucoseStats['readings'] }} readings)</div>
                    <div class="mt-3 bg-gray-700 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $glucoseStats['in_target_percent'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">{{ $glucoseStats['in_target_percent'] }}% in target range</div>
                @else
                    <div class="text-gray-500">No data yet</div>
                @endif
            </div>
        </div>

        <!-- Daily Goals Section -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">Daily Goals</h2>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-400">
                        {{ $dailyGoalStats['completed'] }}/{{ $dailyGoalStats['total'] }} completed today ({{ $dailyGoalStats['percent'] }}%)
                    </span>
                    <button onclick="toggleGoalForm()" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        + Add Goal
                    </button>
                </div>
            </div>

            <!-- Add Goal Form (Hidden by default) -->
            <div id="goalForm" class="hidden mb-4 bg-gray-700/50 rounded-lg p-4">
                <form action="{{ route('goals.store') }}" method="POST" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div>
                        <label class="text-sm text-gray-400">Emoji</label>
                        <input type="text" name="emoji" value="✅" maxlength="10"
                            class="w-16 bg-gray-800 border border-gray-600 rounded px-2 py-2 mt-1 focus:border-emerald-500 focus:outline-none text-center">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="text-sm text-gray-400">Goal Name</label>
                        <input type="text" name="name" required placeholder="e.g., Hit Calorie Target"
                            class="w-full bg-gray-800 border border-gray-600 rounded px-3 py-2 mt-1 focus:border-emerald-500 focus:outline-none">
                    </div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded font-medium transition-colors">
                        Create Goal
                    </button>
                </form>
            </div>

            @if($dailyGoals->count() > 0)
                <!-- Today's Goals Checklist -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
                    @foreach($dailyGoals as $goal)
                        @php
                            $isCompleted = $goal->isCompletedForDate(now());
                        @endphp
                        <div class="flex items-center gap-3 bg-gray-700/30 rounded-lg p-3 {{ $isCompleted ? 'opacity-60' : '' }}">
                            <form action="{{ route('goals.toggle', $goal) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <input type="hidden" name="date" value="{{ now()->format('Y-m-d') }}">
                                <input type="hidden" name="completed" value="{{ $isCompleted ? '0' : '1' }}">
                                <button type="submit" class="w-6 h-6 rounded border-2 {{ $isCompleted ? 'bg-emerald-500 border-emerald-500' : 'border-gray-500 hover:border-emerald-400' }} flex items-center justify-center transition-colors">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <span class="text-lg">{{ $goal->emoji }}</span>
                            <span class="flex-1 {{ $isCompleted ? 'line-through text-gray-500' : '' }}">{{ $goal->name }}</span>
                            <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="flex-shrink-0" onsubmit="return confirm('Delete this goal?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Weekly Progress Chart -->
                <div class="bg-gray-700/30 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Weekly Progress</h3>
                    <div class="flex items-end gap-2 h-16">
                        @foreach($weeklyGoalStats as $day)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-gray-600 rounded-t h-12 relative overflow-hidden">
                                    <div class="absolute bottom-0 w-full bg-emerald-500 rounded-t transition-all" style="height: {{ $day['percent'] }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-1">{{ $day['date'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between mt-2 text-xs text-gray-500">
                        <span>{{ $weeklyGoalStats[0]['date'] }}</span>
                        <span>{{ $weeklyGoalStats[6]['date'] }}</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No custom goals yet. Add your first daily goal above!</p>
                    <p class="text-sm mt-2">Examples: Hit Calorie Target, Brush Teeth, Do daily stretch routine</p>
                </div>
            @endif
        </div>

        <!-- Charts Section - Row 1: Weight & Walking -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Weight Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Weight Trend (Last 14 Days)</h3>
                    <span class="text-emerald-400 text-sm">Goal: -25kg</span>
                </div>
                <canvas id="weightChart" height="200"></canvas>
            </div>

            <!-- Walking Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Walking Distance (Last 7 Days)</h3>
                    <span class="text-blue-400 text-sm">Goal: 3mi/day</span>
                </div>
                <canvas id="walkChart" height="200"></canvas>
            </div>
        </div>

        <!-- Charts Section - Row 2: Water & Mood -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Water Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Water Intake (Last 7 Days)</h3>
                    <span class="text-cyan-400 text-sm">Goal: 8 glasses</span>
                </div>
                <canvas id="waterChart" height="200"></canvas>
            </div>

            <!-- Mood Chart -->
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Mood & Energy (Last 7 Days)</h3>
                    <span class="text-purple-400 text-sm">Score: 1-5</span>
                </div>
                <canvas id="moodChart" height="200"></canvas>
            </div>
        </div>

        <!-- Glucose Chart -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Blood Glucose (Last 7 Days)</h3>
                <span class="text-red-400 text-sm">Target: 4.0-7.0 mmol/L (fasting)</span>
            </div>
            <canvas id="glucoseChart" height="200"></canvas>
        </div>

        <!-- Recent Entries -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Recent Weights</h3>
                <div class="space-y-2">
                    @forelse($recentWeights as $entry)
                        <div class="flex justify-between items-center py-2 border-b border-gray-700">
                            <span class="text-gray-400">{{ $entry->date->format('M j') }}</span>
                            <span class="font-semibold">{{ $entry->weight_kg }}kg</span>
                            @if($entry->notes)
                                <span class="text-xs text-gray-500">{{ Str::limit($entry->notes, 20) }}</span>
                            @endif
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

            <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                <h3 class="text-lg font-semibold mb-4">Recent Glucose</h3>
                <div class="space-y-2">
                    @forelse($recentGlucose as $entry)
                        <div class="flex justify-between items-center py-2 border-b border-gray-700">
                            <span class="text-gray-400">{{ $entry->measured_at->format('M j H:i') }}</span>
                            <span class="font-semibold {{ $entry->isInTargetRange() ? 'text-green-400' : 'text-red-400' }}">{{ $entry->glucose_mmol_l }}</span>
                            <span class="text-xs text-gray-500">{{ \App\Models\GlucoseEntry::READING_TYPES[$entry->reading_type] ?? $entry->reading_type }}</span>
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

        function toggleGoalForm() {
            const form = document.getElementById('goalForm');
            form.classList.toggle('hidden');
        }

        // Chart.js global config
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = '#374151';

        // Weight Chart (Line)
        const weightCtx = document.getElementById('weightChart').getContext('2d');
        new Chart(weightCtx, {
            type: 'line',
            data: {
                labels: @json($weightChart['labels']),
                datasets: [{
                    label: 'Weight (kg)',
                    data: @json($weightChart['data']),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.3,
                    spanGaps: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: '#374151' }, 
                        ticks: { color: '#9ca3af', callback: v => v + 'kg' }
                    },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            }
        });

        // Walk Chart (Bar with goal line)
        const walkCtx = document.getElementById('walkChart').getContext('2d');
        new Chart(walkCtx, {
            type: 'bar',
            data: {
                labels: @json($walkChart['labels']),
                datasets: [{
                    label: 'Distance (mi)',
                    data: @json($walkChart['distance']),
                    backgroundColor: '#3b82f6',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { display: false },
                    annotation: {
                        annotations: {
                            goalLine: {
                                type: 'line',
                                yMin: 3,
                                yMax: 3,
                                borderColor: '#60a5fa',
                                borderWidth: 2,
                                borderDash: [5, 5],
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        grid: { color: '#374151' }, 
                        ticks: { color: '#9ca3af', callback: v => v + 'mi' },
                        suggestedMax: 4
                    },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            }
        });

        // Water Chart (Bar with goal line)
        const waterCtx = document.getElementById('waterChart').getContext('2d');
        new Chart(waterCtx, {
            type: 'bar',
            data: {
                labels: @json($waterChart['labels']),
                datasets: [{
                    label: 'Glasses',
                    data: @json($waterChart['data']),
                    backgroundColor: '#06b6d4',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        grid: { color: '#374151' }, 
                        ticks: { color: '#9ca3af', stepSize: 2 },
                        suggestedMax: 10
                    },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            },
            plugins: [{
                id: 'goalLine',
                afterDraw: (chart) => {
                    const ctx = chart.ctx;
                    const yAxis = chart.scales.y;
                    const xAxis = chart.scales.x;
                    const y = yAxis.getPixelForValue(8);
                    
                    ctx.save();
                    ctx.beginPath();
                    ctx.setLineDash([5, 5]);
                    ctx.strokeStyle = '#22d3ee';
                    ctx.lineWidth = 2;
                    ctx.moveTo(xAxis.left, y);
                    ctx.lineTo(xAxis.right, y);
                    ctx.stroke();
                    ctx.restore();
                }
            }]
        });

        // Mood Chart (Line with multiple datasets)
        const moodCtx = document.getElementById('moodChart').getContext('2d');
        new Chart(moodCtx, {
            type: 'line',
            data: {
                labels: @json($moodChart['labels']),
                datasets: [
                    {
                        label: 'Mood',
                        data: @json($moodChart['mood']),
                        borderColor: '#a855f7',
                        backgroundColor: 'rgba(168, 85, 247, 0.1)',
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: 4,
                    },
                    {
                        label: 'Energy',
                        data: @json($moodChart['energy']),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: 3,
                        borderDash: [5, 5],
                    },
                    {
                        label: 'Sleep',
                        data: @json($moodChart['sleep']),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: 3,
                        borderDash: [2, 2],
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    } 
                },
                scales: {
                    y: { 
                        grid: { color: '#374151' }, 
                        ticks: { color: '#9ca3af', stepSize: 1 },
                        min: 0,
                        max: 10
                    },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            }
        });

        // Glucose Chart (Line with target range)
        const glucoseCtx = document.getElementById('glucoseChart').getContext('2d');
        new Chart(glucoseCtx, {
            type: 'line',
            data: {
                labels: @json($glucoseChart['labels']),
                datasets: [
                    {
                        label: 'Daily Average',
                        data: @json($glucoseChart['average']),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: 5,
                        pointBackgroundColor: '#ef4444',
                    },
                    {
                        label: 'Fasting',
                        data: @json($glucoseChart['fasting']),
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        tension: 0.3,
                        spanGaps: true,
                        pointRadius: 4,
                        borderDash: [5, 5],
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    } 
                },
                scales: {
                    y: { 
                        grid: { color: '#374151' }, 
                        ticks: { color: '#9ca3af', callback: v => v + ' mmol/L' },
                        suggestedMin: 3,
                        suggestedMax: 12
                    },
                    x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
                }
            },
            plugins: [{
                id: 'targetRange',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    const yAxis = chart.scales.y;
                    const xAxis = chart.scales.x;
                    const yMin = yAxis.getPixelForValue(4.0);
                    const yMax = yAxis.getPixelForValue(7.0);
                    
                    ctx.save();
                    ctx.fillStyle = 'rgba(16, 185, 129, 0.15)';
                    ctx.fillRect(xAxis.left, yMax, xAxis.right - xAxis.left, yMin - yMax);
                    ctx.restore();
                }
            }]
        });
    </script>
</body>
</html>
