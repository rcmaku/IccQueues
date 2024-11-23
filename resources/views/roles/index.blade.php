<x-layout :isLoginPage="false">
    <x-slot:heading class="text-center bg-blue-700 text-white">
        Manage User Roles
    </x-slot:heading>

    <div class="container mx-auto p-6">

        <div class="flex justify-end mb-6">
            <a href="{{ route('roles.list') }}" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Manage Roles
            </a>
        </div>

        <h1 class="text-2xl font-bold mb-6">Manage User Roles</h1>

        @foreach($users as $user)
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h4 class="text-xl font-semibold">{{ $user->first_name }} {{ $user->last_name }}</h4>
                <p class="text-gray-700">Email: {{ $user->email }}</p>

                <p class="mt-2 text-gray-800">Roles:
                    @foreach($user->roles as $role)
                        <span class="inline-block bg-blue-200 text-blue-700 px-3 py-1 rounded-full text-sm mr-2">{{ $role->roleName }}</span>
                    @endforeach
                </p>

                <form action="{{ route('roles.assign', $user) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <select name="role_id" class="form-select rounded-lg border-gray-300 shadow-sm p-2" id="roleSelect{{ $user->id }}">
                            <option value="" disabled selected>Choose an option</option> <!-- Default option -->
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->roleName }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition" id="assignRoleButton{{ $user->id }}" disabled>Assign Role</button> <!-- Disabled button initially -->
                    </div>
                </form>

                @foreach($user->roles as $role)
                    <form action="{{ route('roles.remove', $user) }}" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition">Remove {{ $role->roleName }} Role</button>
                    </form>
                @endforeach
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @foreach($users as $user)
            document.getElementById('roleSelect{{ $user->id }}').addEventListener('change', function () {
                const button = document.getElementById('assignRoleButton{{ $user->id }}');

                if (this.value) {
                    button.removeAttribute('disabled');
                } else {
                    button.setAttribute('disabled', true);
                }
            });
            @endforeach
        });
    </script>

    <div id="roleDrawer" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden">
        <div class="fixed inset-0 flex justify-end p-4">
            <div class="w-full md:w-1/3 bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 id="drawerTitle" class="text-xl font-semibold">Create/Edit Role</h2>
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
        function openDrawer(action, role = null) {
            const drawer = document.getElementById('roleDrawer');
            const form = document.getElementById('roleForm');
            const title = document.getElementById('drawerTitle');

            if (action === 'create') {
                title.innerText = 'Create Role';
                form.action = '{{ route('roles.store') }}';
                form.reset();
            } else if (action === 'edit') {
                title.innerText = 'Edit Role';
                form.action = `/roles/${role.id}`;
                document.getElementById('roleName').value = role.roleName;
                document.getElementById('status').value = role.status;
            }

            drawer.classList.remove('hidden');
        }

        function closeDrawer() {
            document.getElementById('roleDrawer').classList.add('hidden');
        }
    </script>

</x-layout>
