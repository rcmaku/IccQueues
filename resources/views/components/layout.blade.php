<!DOCTYPE html>
<html lang="en" class="h-full bg-light-subtle">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ICCQueue</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

</head>
<body class="h-full">

<div class="min-h-full flex flex-col lg:flex-row" x-data="{ open: false }">

    <!-- Sidebar -->
    <div :class="{'w-64': open, 'w-20': !open}" class="bg-light-subtle text-black-50 space-y-6 py-7 px-2 fixed inset-y-0 left-0 transition-all duration-300">

        <!-- Sidebar Logo n Title -->
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

            <div class="text-center h-12 flex items-center justify-center">
                <img src="https://i0.wp.com/iccbpo.com/wp-content/uploads/2024/01/01-LOGO-ICC.png?resize=300%2C92&ssl=1" alt="Logo"
                     :class="{ 'hidden': !open }"
                     class="h-12 w-auto mx-auto">
            </div>
        </div>

        <!-- Navi -->
        <nav class="space-y-4 mt-6">
            @if (Auth::check())
                @if (Auth::user()->hasRole(['admin', 'manager']))
                    <a href="/report" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">

                        <svg fill="#374151" width="32px" height="32px" viewBox="0 0 32.00 32.00" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#374151" stroke-width="0.00032"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>report</title> <path d="M6 11h4v17h-4v-17zM22 16v12h4v-12h-4zM14 28h4v-24h-4v24z"></path> </g></svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Reporting</span>
                    </a>
                    <a href="/agent/" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                        <svg fill="#374151" width="32px" height="32px" viewBox="0 0 30.586 30.586" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g transform="translate(-546.269 -195.397)"> <path d="M572.138,221.245a15.738,15.738,0,0,0-21.065-.253l-1.322-1.5a17.738,17.738,0,0,1,23.741.28Z"></path> <path d="M561.464,204.152a4.96,4.96,0,1,1-4.96,4.96,4.966,4.966,0,0,1,4.96-4.96m0-2a6.96,6.96,0,1,0,6.96,6.96,6.96,6.96,0,0,0-6.96-6.96Z"></path> <path d="M561.562,197.4a13.293,13.293,0,1,1-13.293,13.293A13.308,13.308,0,0,1,561.562,197.4m0-2a15.293,15.293,0,1,0,15.293,15.293A15.293,15.293,0,0,0,561.562,195.4Z"></path> </g> </g></svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Agent</span>
                    </a>
                    <a href="/roles/" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                        <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M6 8H6.01M6 16H6.01M6 12H18M6 12C3.79086 12 2 10.2091 2 8C2 5.79086 3.79086 4 6 4H18C20.2091 4 22 5.79086 22 8C22 10.2091 20.2091 12 18 12M6 12C3.79086 12 2 13.7909 2 16C2 18.2091 3.79086 20 6 20H18C20.2091 20 22 18.2091 22 16C22 13.7909 20.2091 12 18 12" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                        <span :class="{ 'hidden': !open }" class="text-sm">Roles</span>
                    </a>
                @endif
                <a href="/support" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                    <svg width="32px" height="32px" viewBox="0 0 24 24" id="_24x24_On_Light_Support" data-name="24x24/On Light/Support" xmlns="http://www.w3.org/2000/svg" fill="#374151"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <rect id="view-box" width="24" height="24" fill="none"></rect> <path id="Shape" d="M8,17.751a2.749,2.749,0,0,1,5.127-1.382C15.217,15.447,16,14,16,11.25v-3c0-3.992-2.251-6.75-5.75-6.75S4.5,4.259,4.5,8.25v3.5a.751.751,0,0,1-.75.75h-1A2.753,2.753,0,0,1,0,9.751v-1A2.754,2.754,0,0,1,2.75,6h.478c.757-3.571,3.348-6,7.022-6s6.264,2.429,7.021,6h.478a2.754,2.754,0,0,1,2.75,2.75v1a2.753,2.753,0,0,1-2.75,2.75H17.44A5.85,5.85,0,0,1,13.5,17.84,2.75,2.75,0,0,1,8,17.751Zm1.5,0a1.25,1.25,0,1,0,1.25-1.25A1.251,1.251,0,0,0,9.5,17.751Zm8-6.75h.249A1.251,1.251,0,0,0,19,9.751v-1A1.251,1.251,0,0,0,17.75,7.5H17.5Zm-16-2.25v1A1.251,1.251,0,0,0,2.75,11H3V7.5H2.75A1.251,1.251,0,0,0,1.5,8.751Z" transform="translate(1.75 2.25)" fill="#374151"></path> </g></svg>
                    </svg>
                    <span :class="{ 'hidden': !open }" class="text-sm">IT-Queue</span>
                </a>
            @else
                <a href="/login" class="flex items-center space-x-2 py-2.5 px-4 rounded hover:bg-gray-700">
                    <svg width="34px" height="34px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    </svg>
                    <span :class="{ 'hidden': !open }" class="text-sm">Login</span>
                </a>
            @endif
        </nav>


    </div>


    <div :class="{'ml-64': open, 'ml-20': !open}" class="flex-1 flex flex-col w-full transition-all duration-300">

        <nav class="bg-gray-700">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <button class="lg:hidden text-white" @click="open = !open">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" class="h-6 w-6" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

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

        @if (Auth::check())
            <div x-data="{ openModal: false, selectedAgent: 'John Doe' }" class="relative">

                <!-- Button to open modal at the bottom right -->
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg fixed bottom-4 right-4 z-50" @click.prevent="openModal = true">
                    Create Request
                </button>

                <!-- Modal -->
                <div x-show="openModal" x-transition.opacity x-cloak class="fixed inset-0 flex justify-center items-center bg-gray-900 bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg p-6 max-w-lg w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold">Create New Request</h2>
                            <button @click="openModal = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                        </div>
                        <!-- Display the agent assigned to the request -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Assigned Agent</label>
                            <p class="mt-2 text-sm text-gray-600" x-text="`Assigned to: ${selectedAgent}`"></p>
                        </div>
                        <!-- Modal Form -->
                        <form action="{{ route('newRequest') }}" method="POST" id="new-request-form">
                            @csrf

                            <!-- Title Input -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="title" name="title" required class="mt-2 block w-full max-w-lg border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 bg-white">
                            </div>
                            <!-- Channel Select -->
                            <div class="mb-4">
                                <label for="channel" class="block text-sm font-medium text-gray-700">Channel</label>
                                <select id="channel" name="channel" required class="mt-2 block w-full max-w-lg border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 bg-white">
                                    <option value="Whatsapp">Whatsapp</option>
                                    <option value="Slack">Slack</option>
                                    <option value="Email">Email</option>
                                </select>
                            </div>

                            <!-- Request Type Select -->
                            <div class="mb-4">
                                <label for="request-type" class="block text-sm font-medium text-gray-700">Request Type</label>
                                <select id="request-type" name="request_type" class="mt-2 block w-full max-w-lg border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 bg-white">
                                    <option value="Hardware">Computer</option>
                                    <option value="Software">Internet</option>
                                    <option value="Access">Access</option>
                                    <option value="Platform Specific">Platform Specific related</option>

                                </select>
                            </div>

                            <!-- Description Textarea -->
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-2 block w-full max-w-lg border-2 border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2 bg-white" required></textarea>
                            </div>


                            <!-- Hidden Fields -->
                            <input type="hidden" name="start_time" value="{{ now() }}"> <!-- Set current timestamp as start_time -->
                            <input type="hidden" name="status" value="pending"> <!-- Set status to pending -->

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

</body>
</html>
<script>
    document.getElementById('new-request-form').addEventListener('submit', function(event) {
        event.preventDefault();
        // Perform any additional validation or actions before submitting the form, if necessary
        this.submit(); // Submit the form after validation or actions
    });
</script>
