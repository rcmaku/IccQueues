<x-layout :isLoginPage="false">
    <x-slot:heading>Queue Interactions Report</x-slot:heading>
    <div class="container mx-auto mt-8">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('report.generate') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Filter by User</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 ease-in-out">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 ease-in-out" value="{{ request('start_date') }}">
                </div>

                <div class="form-group">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full border-2 border-gray-300 rounded-md shadow-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 ease-in-out" value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Search
                </button>
            </div>
        </form>

        <hr class="my-6">

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 text-left">User</th>
                <th class="py-3 px-4 text-left">Interaction Count</th>
                <th class="py-3 px-4 text-left">Avg Handling Time (HH:MM:SS)</th>
                <th class="py-3 px-4 text-left">First Interaction Date</th>
                <th class="py-3 px-4 text-left">Last Interaction Date</th>
            </tr>
            </thead>
            <tbody class="text-gray-700">
            @forelse ($reportData as $data)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $data->full_name }} <br><small class="text-gray-500">{{ $data->email }}</small></td>
                    <td class="py-3 px-4">{{ $data->interaction_count }}</td>
                    <td class="py-3 px-4">{{ gmdate('H:i:s', $data->avg_handling_time) }}</td>
                    <td class="py-3 px-4">{{ $data->first_interaction_date }}</td>
                    <td class="py-3 px-4">{{ $data->last_interaction_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-3 px-4 text-center text-gray-500">No data available for the selected criteria.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-layout>
