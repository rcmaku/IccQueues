<!DOCTYPE html>
<html lang="en" class="h-full bg-light-subtle">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ICCQueue</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AlpineJS for Dropdown Toggle -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="h-full">

<div class="min-h-full flex flex-col lg:flex-row" x-data="{ open: true }">

    <!-- Sidebar -->
    <div :class="{'w-64': open, 'w-20': !open}" class="bg-light-subtle text-black-50 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transition-all duration-300">

        <!-- Sidebar Logo and Title -->
        <div class="justify-content-between">
            <button @click="open = !open" class="text-white absolute transform p-3 rounded-full hover:bg-gray-200 transition-all duration-300">
                <svg width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M2 12.32H22" stroke="#000000" stroke-width="1.4249999999999998" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M2 18.32H22" stroke="#000000" stroke-width="1.4249999999999998" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M2 6.32001H22" stroke="#000000" stroke-width="1.4249999999999998" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </button>

            <!-- Add a fixed height for the logo container -->
            <div class="text-center h-12 flex items-center justify-center">
                <!-- Show/hide the logo without changing the container height -->
                <img src="https://i0.wp.com/iccbpo.com/wp-content/uploads/2024/01/01-LOGO-ICC.png?resize=300%2C92&ssl=1" alt="Logo"
                     :class="{ 'hidden': !open }"
                     class="h-12 w-auto mx-auto">
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-4 mt-6">
            @if (Auth::check())
                @if (Auth::user()->hasRole(['admin', 'manager']))
                    <a href="/report" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                        <!-- SVG and text for Reporting -->
                        <svg fill="#ff4500" width="34px" height="34px" viewBox="0 0 30.59 30.59" xmlns="http://www.w3.org/2000/svg" stroke="#ff4500" stroke-width="0.00030586">
                            <!-- SVG content -->
                        </svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Reporting</span>
                    </a>
                    <a href="/agent/" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                        <!-- SVG and text for Agent -->
                        <svg fill="#0011ff" height="34px" width="34px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg">
                            <!-- SVG content -->
                        </svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Agent</span>
                    </a>
                    <a href="/roles/" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                        <!-- SVG and text for Roles -->
                        <svg fill="#4CAF50" width="34px" height="34px" viewBox="0 0 30.59 30.59" xmlns="http://www.w3.org/2000/svg" stroke="#4CAF50" stroke-width="0.00030586">
                            <!-- SVG content -->
                        </svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Roles</span>
                    </a>
                @endif
                <!-- Other buttons visible to all authenticated users -->
                <a href="/support" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                    <!-- SVG and text for IT-Queue -->
                    <svg width="34px" height="34px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- SVG content -->
                    </svg>
                    <span :class="{ 'hidden': !open }" class="text-sm">IT-Queue</span>
                </a>
            @else
                <a href="/login" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                    <!-- SVG and text for Login -->
                    <svg width="34px" height="34px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- SVG content -->
                    </svg>
                    <span :class="{ 'hidden': !open }" class="text-sm">Login</span>
                </a>
            @endif
        </nav>


    </div>


    <!-- Main Content (flexible) -->
    <div :class="{'ml-64': open, 'ml-20': !open}" class="flex-1 flex flex-col w-full transition-all duration-300">

        <nav class="bg-gray-700">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Menu Button (Visible on small screens) -->
                    <button class="lg:hidden text-white" @click="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="h-6 w-6" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <!-- User Info or Default Header -->
                    @if (Auth::check())
                        <div class="flex items-center space-x-4 ml-auto relative" x-data="{ open: false }">
                            <p class="text-white text-sm font-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <button @click="open = !open" class="flex items-center space-x-2 text-white">
                                <div class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center text-lg font-semibold">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                                </div>
                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 9l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="fixed top-16 right-4 w-64 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 mt-2">
                                <div class="py-2 px-4 bg-gray-600">
                                    <p class="text-white text-sm font-semibold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                    <p class="text-white text-xs">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="mt-4 space-y-2">
                                    <a href="/profile" class="block text-gray-700 text-sm px-4 py-2 hover:bg-gray-100 rounded-md">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="block text-gray-700 text-sm px-4 py-2 hover:bg-gray-100 w-full text-left rounded-md">Logout</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <h1 class="text-lg font-bold text-white">ICCQueue</h1>
                    @endif
                </div>
            </div>
        </nav>

        <header class="bg-white shadow {{ $isLoginPage ? 'hidden' : '' }}">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-center font-extrabold text-4xl text-gray-900 tracking-tight leading-tight">{{ $heading ?? 'Default Heading' }}</h1>
            </div>
        </header>

        <main class="flex-1 p-6 bg-gray-100">
            {{ $slot }}  <!-- Here is where the content will be injected from views -->
        </main>
    </div>
</div>

<!-- AlpineJS Script for Sidebar Toggle -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sidebar', () => ({
            open: true,
        }))
    })
</script>

</body>
</html>
