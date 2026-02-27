<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-bg text-text transition-colors duration-300">

    <livewire:header />

    <main class="max-w-6xl mx-auto px-4 py-10">
        @yield('content')
    </main>

    <livewire:footer />
    @livewireScripts
    @fluxScripts
</body>

</html>
