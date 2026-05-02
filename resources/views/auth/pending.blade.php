<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Account Pending - SafetyNet</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>body { font-family: 'Inter', sans-serif; }</style>
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen p-6">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-10 text-center border border-gray-100">
            <div class="w-24 h-24 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Verification Pending</h1>
            <p class="text-gray-600 mb-8 leading-relaxed">
                Thank you for joining <strong>SafetyNet</strong>. Your account is currently pending approval by an administrator. This is part of our commitment to community safety.
            </p>
            
            <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl mb-8">
                <p class="text-amber-800 text-sm font-medium">
                    You will gain access to the dashboard once an administrator approves your request.
                </p>
            </div>
            
            <div class="space-y-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        Logout and Return Later
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
