<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Habit Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-6">
        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700">
            <div class="text-center mb-8">
                <div class="mx-auto w-16 h-16 bg-emerald-900/50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-emerald-400">Verify Your Email</h1>
                <p class="text-gray-400 mt-2">We've sent a verification link to your email address.</p>
            </div>

            @if(session('status') == 'verification-link-sent')
                <div class="mb-6 bg-emerald-900/50 border border-emerald-500 text-emerald-300 px-4 py-3 rounded-lg text-sm">
                    A new verification link has been sent to your email address.
                </div>
            @endif

            <div class="space-y-4">
                <p class="text-gray-400 text-sm text-center">
                    Before you can access your dashboard, please verify your email address by clicking the link in the email we sent you.
                </p>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 py-3 rounded-lg font-medium transition-colors">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gray-700 hover:bg-gray-600 py-3 rounded-lg font-medium transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
