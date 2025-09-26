<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'Security Audit Tool' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">{{ $title ?? 'Security Audit Tool' }}</h1>
            <p class="text-sm text-gray-500">{{ $subtitle ?? '' }}</p>
        </div>

        {{-- This is where child views will be injected --}}
        {{ $slot }}
    </div>

</body>
</html>
