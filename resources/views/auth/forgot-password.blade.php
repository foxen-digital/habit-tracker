<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Habit Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-emerald-400">Forgot Password?</h1>
                <p class="text-gray-400 mt-2">Enter your email and we'll send you a reset link.</p>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 focus:border-emerald-500 focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3 rounded-lg font-medium transition-colors">
                    Send Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 text-sm">
                    ← Back to login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
