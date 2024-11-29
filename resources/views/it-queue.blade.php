<x-layout :isLoginPage="false">
    <x-slot:heading>IT Queue</x-slot:heading>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- First Grid: Users and Statuses (Upcoming Specialist + IT Specialists) -->
            <div class="bg-white p-6 rounded-lg shadow col-span-1">
                <h2 class="text-2xl font-bold text-gray-700 mb-6">IT Queue</h2>

                <!-- Upcoming Specialist Section -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 text-center">Upcoming Specialist</h3>
                    @isset($upcomingUser)
                        <div class="text-center mt-4">
                            <p class="text-lg font-medium">
                                {{ $upcomingUser['first_name'] ?? 'Unknown' }} {{ $upcomingUser['last_name'] ?? '' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Status: {{ $upcomingUser['agentStatus']['name'] ?? 'No status' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Available since: {{ \Carbon\Carbon::parse($upcomingUser['available_at'])->diffForHumans() }}
                            </p>
                        </div>
                    @else
                        <p class="text-center text-gray-600 mt-4">No upcoming user in line.</p>
                    @endisset
                </div>

                <!-- IT Specialists Section (Available) -->
                <div>
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Available IT Specialists</h3>
                    @foreach ($users as $user)
                        @if ($user['agentStatus'] && $user['agentStatus']['name'] === 'available')
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        #{{ $loop->index + 1 }}
                                    </p>
                                    <p class="text-lg font-medium text-gray-700">
                                        {{ $user['first_name'] }} {{ $user['last_name'] }}
                                    </p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span>{{ $user['agentStatus']['name'] ?? 'No status' }}</span>
                                    <span id="timer-{{ $user['id'] }}" class="ml-2 text-green-500">
                                        @php
                                            $availableAt = \Carbon\Carbon::parse($user['available_at']);
                                        @endphp
                                        {{ $availableAt->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- IT Specialists Section (Unavailable) -->
                <div class="mt-6">
                    <h3 class="text-xl font-bold text-gray-700 mb-4">Unavailable IT Specialists</h3>
                    @foreach ($users as $user)
                        @if ($user['agentStatus'] && $user['agentStatus']['name'] !== 'available')
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-2">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        #{{ $loop->index + 1 }}
                                    </p>
                                    <p class="text-lg font-medium text-gray-700">
                                        {{ $user['first_name'] }} {{ $user['last_name'] }}
                                    </p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span>{{ $user['agentStatus']['name'] ?? 'No status' }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <!-- Second Grid: Your Status -->
            <div class="bg-white p-6 rounded-lg shadow col-span-1">
                <h3 class="text-xl font-semibold text-gray-700 text-center mb-6">Your Status</h3>

                <!-- Form to Mark User as Available -->
                <form action="{{ route('markAvailable') }}" method="POST" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition">
                        Mark as Available
                    </button>
                </form>

                <!-- Update Status Modal -->
                <div x-data="{ isModalOpen: false, selectedStatus: '' }">
                    <button @click="isModalOpen = true" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition">
                        Update Status
                    </button>

                    <div x-show="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75" style="display: none;">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                            <h2 class="text-lg font-bold mb-4 text-center">Update Your Status</h2>
                            <form method="POST" action="{{ route('updateStatus', ['user' => auth()->user()->id]) }}">
                                @csrf
                                <div class="space-y-3">
                                    @foreach ($availableStatuses as $status)
                                        @if (!in_array($status->name, ['available', 'offline']))
                                            <label class="flex items-center">
                                                <input type="radio" name="status" value="{{ $status->name }}" x-model="selectedStatus" class="form-radio text-blue-500">
                                                <span class="ml-2">{{ ucfirst($status->name) }}</span>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="mt-6 flex justify-end gap-4">
                                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition">
                                        Confirm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Third Grid: Your Tasks -->
            <div class="bg-white p-6 rounded-lg shadow col-span-1">
                <h3 class="text-xl font-semibold text-gray-700 text-center mb-6">Task Status</h3>
                @php
                    // Filter out completed tasks from the collection
                    $filteredRequests = $requests->filter(function($request) {
                        return $request->status !== 'Completed';
                    });
                @endphp

                @if ($filteredRequests->isEmpty())
                    <p class="text-gray-600 text-center">No pending tasks available.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($filteredRequests as $request)
                            <li class="p-4 bg-gray-50 rounded-lg border border-gray-300">
                                <h4 class="text-lg font-semibold text-gray-700">{{ $request->title }}</h4>
                                <p class="text-sm text-gray-500">Channel: {{ $request->channel }}</p>
                                <p class="text-sm text-gray-500">Assigned To: {{ $request->user->fullName() ?? 'Unassigned' }}</p>
                                <p class="text-sm text-gray-500">Submitted: {{ $request->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-gray-500">Status: {{ $request->status }}</p>

                                @if ($request->user_id === auth()->id())
                                    <!-- Mark Task as Complete -->
                                    <form action="{{ route('tasks.complete', $request->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition">
                                            Mark as Complete
                                        </button>
                                    </form>

                                    <!-- Skip Task -->
                                    <form action="{{ route('tasks.skip', $request->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition">
                                            Skip Task
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <script>
        setInterval(function() {
            @foreach ($users as $user)
            const availableTime = new Date("{{ \Carbon\Carbon::parse($user['available_at'])->toDateTimeString() }}").getTime();
            const now = new Date().getTime();
            const timeDiff = now - availableTime;

            const seconds = Math.floor(timeDiff / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);

            const hoursDisplay = hours < 10 ? "0" + hours : hours;
            const minutesDisplay = minutes % 60 < 10 ? "0" + (minutes % 60) : minutes % 60;
            const secondsDisplay = seconds % 60 < 10 ? "0" + (seconds % 60) : seconds % 60;

            document.getElementById("timer-{{ $user['id'] }}").innerText = hoursDisplay + ":" + minutesDisplay + ":" + secondsDisplay;
            @endforeach
        }, 1000);
    </script>
</x-layout>
