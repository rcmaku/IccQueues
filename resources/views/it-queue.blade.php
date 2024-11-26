<x-layout :isLoginPage="false">
    <x-slot:heading>IT Queue</x-slot:heading>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-4 gap-6">

            <!-- Users/Statuses Column -->
            <div class="col-span-1 bg-white p-4 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Users/Status/Duration</h2>
                <div id="users-list" class="space-y-4">
                    @foreach ($users as $user)
                        @php
                            $statusUpdatedAt = $user->agentStatusHistory->sortByDesc('changed_at')->first()?->changed_at;
                            $statusUpdatedAt = $statusUpdatedAt ? \Carbon\Carbon::parse($statusUpdatedAt) : null;
                        @endphp
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg user-row">
                            <div>
                                <span class="mr-2 text-sm text-gray-500">{{ $user->AgentOrder }}</span>
                                <span class="text-lg font-medium text-gray-700">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                            <div class="text-sm text-gray-600 flex items-center">
                                <span class="status-name">{{ $user->agentStatus->name ?? 'No status' }}</span>
                                @if ($statusUpdatedAt)
                                    <span class="ml-2 text-xs text-gray-500 timer" data-timestamp="{{ $statusUpdatedAt->toIso8601String() }}"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Your Tasks Section with Next User in Line & Task Status -->
            <div class="col-span-2 bg-white p-4 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-gray-700 text-center mb-4">Your Status</h3>
                @foreach ($users as $user)
                    @if ($user->id === auth()->user()->id)
                        <div class="bg-yellow-100 p-4 rounded-lg mt-4">
                            <p class="text-gray-700">New task assigned:</p>
                            <p class="font-semibold">{{ optional($user->tasks)->last()?->title ?? 'No tasks assigned.' }}</p>
                            <form action="{{ route('updateStatus') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700">Change Status</label>
                                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                        @foreach ($availableStatuses as $status)
                                            @if (!in_array($status->name, ['available', 'offline']))
                                                <option value="{{ $status->name }}" {{ $user->agentStatus?->name === $status->name ? 'selected' : '' }}>
                                                    {{ ucfirst($status->name) }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <!-- Next User In Line (Within Your Tasks) -->
                        <div class="mt-6">
                            <h3 class="text-xl font-semibold text-gray-700 text-center mb-4">Next User In Line</h3>
                            @if ($nextUser)
                                <div>
                                    <p class="text-lg font-medium">{{ $nextUser->first_name }} {{ $nextUser->last_name }}</p>
                                    <p class="text-sm text-gray-500">Current Status: {{ $nextUser->agentStatus?->name ?? 'No status' }}</p>
                                </div>
                                <!-- Mark as Available Button -->
                                <form action="{{ route('markAsAvailable') }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition">
                                        Mark as Available
                                    </button>
                                </form>
                            @else
                                <p class="text-gray-600">No user is currently next in line.</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Task Status Section -->
            <div class="col-span-1 bg-white p-4 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-gray-700 text-center mb-4">Task Status</h3>
                @if ($requests->isEmpty())
                    <p class="text-gray-600 text-center">No requests available.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($requests as $request)
                            @if ($request->status !== 'Completed') <!-- Ensure task is not completed -->
                            <li class="p-4 border border-gray-300 rounded-lg">
                                <h4 class="text-lg font-semibold text-gray-700">{{ $request->title }}</h4>
                                <p class="text-sm text-gray-500">Channel: {{ $request->channel }}</p>
                                <p class="text-sm text-gray-500">
                                    Assigned To: {{ $request->user->fullName() ?? 'Unassigned' }}
                                </p>
                                <p class="text-sm text-gray-500">Submitted: {{ $request->created_at->diffForHumans() }}</p>
                                <p class="text-sm text-gray-500">Status: {{ $request->status }}</p>

                                <!-- Mark as Complete Button -->
                                @if ($request->user_id === auth()->id() && $request->status !== 'Completed')
                                    <form action="{{ route('requests.complete', $request->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition">
                                            Mark as Complete
                                        </button>
                                    </form>
                                @elseif ($request->status === 'Completed')
                                    <p class="text-sm text-green-500">This request is already completed.</p>
                                @endif

                                <!-- Skip Task Button -->
                                @if ($request->user_id === auth()->id() && $request->status !== 'Completed')
                                    <form action="{{ route('tasks.skip', $request->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        @method('PATCH') <!-- Use PATCH as we are updating the task -->
                                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 text-white rounded-lg font-medium hover:bg-yellow-600 transition">
                                            Skip Task
                                        </button>
                                    </form>
                                @endif
                            </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.timer').forEach(timer => {
            const timestamp = new Date(timer.dataset.timestamp);
            function updateTimer() {
                const now = new Date();
                const diff = Math.floor((now - timestamp) / 1000);
                const hours = Math.floor(diff / 3600);
                const minutes = Math.floor((diff % 3600) / 60);
                const seconds = diff % 60;
                timer.textContent = `${hours}h ${minutes}m ${seconds}s ago`;
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    </script>
</x-layout>
