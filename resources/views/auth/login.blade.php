<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Habit Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-emerald-400">Habit Tracker</h1>
                <p class="text-gray-400 mt-2">Welcome back! Please sign in.</p>
            </div>

            @if(session('status'))
                <div class="mb-4 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm text-gray-400 mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded bg-gray-700 border-gray-600 text-emerald-500 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-gray-400">Remember me</span>
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-emerald-400 hover:text-emerald-300">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3 rounded-lg font-medium transition-colors">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
