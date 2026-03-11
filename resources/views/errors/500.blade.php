<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 Server Error - Agency Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <div class="mx-auto h-24 w-24 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-8">
            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Internal Server Error</h1>
        <p class="text-gray-500 mb-8">Something went wrong on our end. Our team has been notified and is working to fix
            the issue. Please try again later.</p>
        <button onclick="window.location.reload()"
            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Refresh Page
        </button>
    </div>
</body>

</html>