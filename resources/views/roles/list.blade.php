<x-layout :isLoginPage="false">
    <x-slot:heading class="text-center bg-blue-700 text-white">
        List of Roles
    </x-slot:heading>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

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
                    <th class="px-6 py-3 text-left">Role Name</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr class="border-t hover:bg-gray-100">
                        <td class="px-6 py-4 text-sm">{{ $role->roleName }}</td>
                        <td class="px-6 py-4 text-sm">{{ $role->status ? 'Active' : 'Inactive' }}</td>
                        <td class="px-6 py-4 text-center">
                            <!-- Delete Button -->
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Drawer for Create/Edit Role -->
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
        function openDrawer(action, roleId = null) {
            const drawer = document.getElementById('roleDrawer');
            const drawerContent = document.getElementById('drawerContent');
            const form = document.getElementById('roleForm');
            const title = document.getElementById('drawerTitle');

            if (action === 'create') {
                title.innerText = 'Create Role';
                form.action = '{{ route('roles.store') }}';
                form.reset();
            } else if (action === 'edit' && roleId) {
                title.innerText = 'Edit Role';
                form.action = '/roles/' + roleId;  // This is the correct route for updating the role

                // Fetch role data from the server and populate form fields
                fetch(`/roles/${roleId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('roleName').value = data.roleName;
                        document.getElementById('status').value = data.status;
                    })
                    .catch(error => console.error('Error fetching role data:', error));
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
