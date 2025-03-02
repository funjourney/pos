<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia="true">{{ config('app.name', 'Laravel') }}</title>

    @env('local')
        @viteReactRefresh
    @endenv

    @vite(['resources/js/app.tsx'])    
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
