<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Habit Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-emerald-400">Settings</h1>
                    <p class="text-gray-400 mt-1">Customize your habit tracking experience</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-gray-700 hover:bg-gray-600 px-4 py-2 rounded-lg font-medium transition-colors">
                    ← Back to Dashboard
                </a>
            </div>
        </header>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Settings Form -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h2 class="text-xl font-semibold mb-6 text-emerald-400">Your Goals</h2>

            <form method="POST" action="{{ route('settings.update') }}">
                @csrf

                <div class="space-y-6">
                    <!-- Weight Goal -->
                    <div>
                        <label for="weight_goal_kg" class="block text-sm font-medium text-gray-300 mb-2">
                            Weight Loss Goal (kg)
                        </label>
                        <input type="number" id="weight_goal_kg" name="weight_goal_kg" step="0.1" min="1" max="200"
                            value="{{ $settings->weight_goal_kg }}" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <p class="text-gray-500 text-sm mt-1">How much weight do you want to lose?</p>
                        @error('weight_goal_kg')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Walking Target -->
                    <div>
                        <label for="daily_walk_target_miles" class="block text-sm font-medium text-gray-300 mb-2">
                            Daily Walking Target (miles)
                        </label>
                        <input type="number" id="daily_walk_target_miles" name="daily_walk_target_miles" step="0.1" min="0" max="50"
                            value="{{ $settings->daily_walk_target_miles }}" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <p class="text-gray-500 text-sm mt-1">Your daily distance goal</p>
                        @error('daily_walk_target_miles')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Water Target -->
                    <div>
                        <label for="daily_water_target_glasses" class="block text-sm font-medium text-gray-300 mb-2">
                            Daily Water Target (glasses)
                        </label>
                        <input type="number" id="daily_water_target_glasses" name="daily_water_target_glasses" min="1" max="20"
                            value="{{ $settings->daily_water_target_glasses }}" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none">
                        <p class="text-gray-500 text-sm mt-1">Recommended: 8 glasses per day</p>
                        @error('daily_water_target_glasses')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="border-gray-700 my-8">

                <h2 class="text-xl font-semibold mb-6 text-emerald-400">Preferences</h2>

                <div class="space-y-6">
                    <!-- Weight Unit -->
                    <div>
                        <label for="weight_unit" class="block text-sm font-medium text-gray-300 mb-2">
                            Weight Unit
                        </label>
                        <select id="weight_unit" name="weight_unit" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none">
                            <option value="kg" {{ $settings->weight_unit === 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                            <option value="lbs" {{ $settings->weight_unit === 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                        </select>
                        @error('weight_unit')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Distance Unit -->
                    <div>
                        <label for="distance_unit" class="block text-sm font-medium text-gray-300 mb-2">
                            Distance Unit
                        </label>
                        <select id="distance_unit" name="distance_unit" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none">
                            <option value="miles" {{ $settings->distance_unit === 'miles' ? 'selected' : '' }}>Miles</option>
                            <option value="km" {{ $settings->distance_unit === 'km' ? 'selected' : '' }}>Kilometers (km)</option>
                        </select>
                        @error('distance_unit')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3 rounded-lg font-medium transition-colors">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Section -->
        <div class="mt-8 bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h2 class="text-xl font-semibold mb-4 text-emerald-400">Account</h2>
            <p class="text-gray-400 mb-4">Logged in as: <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-medium transition-colors">
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
