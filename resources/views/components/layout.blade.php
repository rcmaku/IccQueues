<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ICCQueue</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">
<div class="min-h-full flex">

    <!-- Sidebar Toggle Checkbox -->
    <input type="checkbox" id="sidebar-toggle" class="hidden peer">

    <!-- Sidebar -->
    <div class="bg-gray-800 text-white w-64 space-y-6 py-7 px-2 transform -translate-x-64 peer-checked:translate-x-0 transition-transform duration-300 fixed inset-y-0 left-0">
        <!-- Logo -->
        <div class="text-center">
            <img src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Logo" class="h-12 w-auto mx-auto">
            <h1 class="text-2xl font-bold mt-2">ICCQueue</h1>
        </div>

        <!-- Navigation -->
        <nav class="space-y-4">
            <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Dashboard</a>
            <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">New Request</a>
            <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Profile</a>
            <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Settings</a>
            <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-700">Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Top Navigation Bar -->
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Sidebar Toggle Button -->
                    <label for="sidebar-toggle" class="cursor-pointer text-gray-400 hover:text-white">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </label>
                    <h1 class="text-lg font-bold text-white">ICCQueue</h1>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $heading }}</h1>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6 bg-gray-100">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
