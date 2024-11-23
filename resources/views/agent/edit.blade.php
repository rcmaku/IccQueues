<form action="{{ route('agent.update', $agent->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- Use PUT method for updates -->

    <div class="mb-4">
        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
        <input type="text" name="first_name" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 text-gray-800"
               value="{{ old('first_name', $agent->first_name) }}" required>
    </div>

    <div class="mb-4">
        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
        <input type="text" name="last_name" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 text-gray-800"
               value="{{ old('last_name', $agent->last_name) }}">
    </div>

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address:</label>
        <input type="email" name="email" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 text-gray-800"
               value="{{ old('email', $agent->email) }}">
    </div>

    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
        <input type="password" name="password" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 text-gray-800">
    </div>

    <!-- AgentOrder Field -->
    <div class="mb-4">
        <label for="AgentOrder" class="block text-sm font-medium text-gray-700">Agent Order:</label>
        <input type="number" name="AgentOrder" class="form-input mt-1 block w-full border-2 border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 bg-gray-50 text-gray-800"
               value="{{ old('AgentOrder', $agent->AgentOrder) }}">
    </div>

    <!-- Buttons for Submit and Cancel -->
    <div class="flex justify-between mt-6 gap-4">
        <button type="submit" class="bg-blue-600 text-white rounded-lg p-4 w-full flex justify-center items-center text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Submit
        </button>

        <a href="{{ route('agent.index') }}" class="bg-red-600 text-white rounded-lg p-4 w-full flex justify-center items-center text-lg font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
            Cancel
        </a>
    </div>
</form>
