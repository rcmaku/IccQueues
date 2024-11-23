<x-layout :isLoginPage="false">
    <x-slot:heading class="text-center bg-blue-700 text-white">
        List of Roles
    </x-slot:heading>

    <div class="container mx-auto p-6">

        <div class="flex justify-end mb-6">
            <button onclick="openDrawer('create')" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Create Role
            </button>
        </div>

        <h1 class="text-2xl font-bold mb-6">All Roles</h1>

        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full table-auto bg-white">
                <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="px-6 py-3 text-left">User ID</th>
                    <th class="px-6 py-3 text-left">User Name</th>
                    <th class="px-6 py-3 text-left">Assigned Roles</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="border-t hover:bg-gray-100">
                        <td class="px-6 py-4 text-sm">{{ $user->id }}</td>
                        <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $user->roles->pluck('roleName')->join(', ') }}</td>
                        <td class="px-6 py-4 text-center">

                            <form action="{{ route('roles.assign', ['user' => $user->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <div class="flex items-center space-x-2">
                                    <label for="role" class="text-sm font-medium text-gray-700">Assign Role:</label>
                                    <select name="role" id="role" class="border-gray-300 rounded-md text-sm p-2">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->roleName }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-500 transition">
                                        Assign
                                    </button>
                                </div>
                            </form>


                            <form action="{{ route('roles.remove', ['user' => $user->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition">
                                    Remove Role
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div id="roleDrawer" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden">
        <div class="fixed inset-0 flex justify-end p-4">
            <div class="w-full md:w-1/3 bg-white shadow-xl rounded-lg overflow-hidden transform translate-x-full transition-transform duration-300" id="drawerContent">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 id="drawerTitle" class="text-xl font-semibold">Create Role</h2>
                    <button onclick="closeDrawer()" class="text-gray-500 hover:text-gray-800">&times;</button>
                </div>

                <form id="roleForm" action="{{ route('roles.store') }}" method="POST" class="p-4">
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

                    <div class="flex justify-between">
                        <button type="button" onclick="closeDrawer()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</button>
                        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Save Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDrawer(action) {
            const drawer = document.getElementById('roleDrawer');
            const drawerContent = document.getElementById('drawerContent');
            const form = document.getElementById('roleForm');
            const title = document.getElementById('drawerTitle');

            if (action === 'create') {
                title.innerText = 'Create Role';
                form.action = '{{ route('roles.store') }}';
                form.reset();
            }

            drawer.classList.remove('hidden');
            drawerContent.classList.remove('translate-x-full');
            drawerContent.classList.add('translate-x-0');
        }

        function closeDrawer() {
            const drawer = document.getElementById('roleDrawer');
            const drawerContent = document.getElementById('drawerContent');

            drawerContent.classList.remove('translate-x-0');
            drawerContent.classList.add('translate-x-full');


            setTimeout(() => {
                drawer.classList.add('hidden');
            }, 300);
        }
    </script>

</x-layout>
