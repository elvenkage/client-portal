<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 Not Found - Agency Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="mx-auto h-24 w-24 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-8">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Page Not Found</h1>
        <p class="text-gray-500 mb-8">Sorry, we couldn't find the page you're looking for. It might have been moved or
            doesn't exist.</p>
        <a href="{{ url('/') }}"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Return Home
        </a>
    </div>
</body>

</html>