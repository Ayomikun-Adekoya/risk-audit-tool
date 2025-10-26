{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SecureAudit') }}</title>

    {{-- Tailwind + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <!-- Shared Navbar -->
        @include('layouts.navbar')

        <!-- Page Content -->
        <main class="flex-1 pt-28"> {{-- added more top padding for gradient navbar --}}
            <div class="max-w-7xl mx-auto px-4">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
    