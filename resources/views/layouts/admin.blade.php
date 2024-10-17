<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex flex-col justify-start items-center pt-0 bg-gray-100">
    <div class="px-6 w-full h-12 flex flex-row items-center justify-between">
        <div class="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500"/>
            </a>
        </div>
        <div class="flex flex-row items-center gap-4">
            @can('admin')
                <a class="@if(request()->is('admin')) text-black @else text-gray-400 @endif"
                   href="{{ route('admin.index') }}">Users</a>
                <a class="@if(request()->is('admin/codes')) text-black @else text-gray-400 @endif"
                   href="{{ route('admin.codes') }}">Codes</a>
                <a class="@if(request()->is('admin/news')) text-black @else text-gray-400 @endif"
                   href="{{ route('admin.news') }}">News</a>
            @endcan
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a href="#"
                   onclick="event.preventDefault();
                                                this.closest('form').submit();">
                    {{ __('Log Out') }}
                </a>
            </form>
        </div>
    </div>
    <div class="w-full max-w-[877px] flex flex-col gap-6">
        {{ $slot }}
    </div>
</div>
</body>
</html>
