<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden - Agency Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="mx-auto h-24 w-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mb-8">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
        <p class="text-gray-500 mb-8">
            {{ $exception->getMessage() ?: 'You do not have permission to access this resource or perform this action.' }}
        </p>
        <div class="space-x-4">
            <button onclick="window.history.back()"
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Go Back
            </button>
            <a href="{{ url('/') }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Return Home
            </a>
        </div>
    </div>
</body>

</html>