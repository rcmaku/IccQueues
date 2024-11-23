<!-- resources/views/admin/show.blade.php -->
<x-layout>
    <x-slot:heading class="text-center bg-blue-700 text-white">
        Role Details
    </x-slot:heading>

    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Role Name: {{ $role->roleName }}</h2>
            <p class="mb-4">Status: {{ $role->status ? 'Active' : 'Inactive' }}</p>

            <div class="flex justify-end">
                <a href="{{ route('admin.index') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Back to Roles</a>
            </div>
        </div>
    </div>
</x-layout>
