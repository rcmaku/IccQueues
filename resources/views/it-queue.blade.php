<x-layout :isLoginPage="false">
    <x-slot:heading>
        IT Queue
    </x-slot:heading>

    <div class="container mx-auto px-4 py-6">
        <!-- Grid Container -->
        <div class="grid grid-cols-3 gap-6">
            <!-- Users and Statuses Column -->
            <div class="col-span-1 bg-white p-4 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Users and Statuses</h2>
                <div id="users-list" class="space-y-4">
                    @foreach ($users as $user)
                        @php
                            $statusUpdatedAt = $user->agentStatusHistory->sortByDesc('changed_at')->first()?->changed_at;
                            $statusUpdatedAt = $statusUpdatedAt ? \Carbon\Carbon::parse($statusUpdatedAt) : null;
                        @endphp
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg user-row" id="user-{{ $user->id }}">
                            <div>
                                <span class="mr-2 text-sm text-gray-500">{{ $user->AgentOrder }}</span>
                                <span class="text-lg font-medium text-gray-700">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                            <div class="text-sm text-gray-600 flex items-center">
                                <span class="status-name">{{ $user->agentStatus ? $user->agentStatus->name : 'No status' }}</span>
                                @if ($statusUpdatedAt)
                                    <span class="ml-2 text-xs text-gray-500 timer" data-timestamp="{{ $statusUpdatedAt->toIso8601String() }}"></span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="col-span-1 bg-white p-4 rounded-lg shadow text-center">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Your Status</h2>
                <p class="text-lg font-medium text-gray-700">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                <p class="text-lg font-medium text-gray-700">
                    Your current status: <span class="font-bold">{{ auth()->user()->agentStatus ? auth()->user()->agentStatus->name : 'No status' }}</span>
                </p>

                @if (session('new_status'))
                    <p class="text-lg font-medium text-gray-700 mt-2">Your status was updated to: <span class="font-bold">{{ session('new_status') }}</span></p>
                @endif

                @if (auth()->user()->agentStatus && auth()->user()->agentStatus->name === 'available')
                    <form action="{{ route('updateStatus') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition">
                            Mark as Busy
                        </button>
                    </form>
                @endif
            </div>

            <div class="col-span-1 bg-white p-4 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-gray-700 text-center mb-4">Task Status</h3>
                @if (auth()->user()->agentStatus && auth()->user()->agentStatus->name === 'available')
                    <p class="text-gray-600 text-center">No tasks currently being worked on</p>
                @elseif (auth()->user()->agentStatus && auth()->user()->agentStatus->name === 'unavailable')
                    <form action="{{ route('completeTask') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="text-center">
                            <label for="worker-gif" class="block text-sm font-medium text-gray-700 mb-2">Current Task</label>
                            <div>
                                <img src="path/to/worker-gif.gif" alt="Worker" class="mx-auto h-16 w-16">
                            </div>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition">
                            Task Complete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.timer').forEach(timer => {
            const timestamp = new Date(timer.dataset.timestamp);
            function updateTimer() {
                const now = new Date();
                const diff = Math.floor((now - timestamp) / 1000); // difference in seconds
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
