<!-- resources/views/roles/create.blade.php -->

<x-layout :isLoginPage="false">

    <x-slot:heading class="text-center bg-blue-700 text-white">
        Create New Role
    </x-slot:heading>

    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Create a New Role</h1>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="roleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" name="roleName" id="roleName" class="mt-1 block w-full border-gray-300 rounded-md" required>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="mb-6">
                <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Create Role</button>
            </div>
        </form>
    </div>

</x-layout>
