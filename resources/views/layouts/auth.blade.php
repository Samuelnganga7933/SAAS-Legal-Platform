<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Le Nium Legal - Authentication')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    @if ($message = Session::get('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ $message }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
