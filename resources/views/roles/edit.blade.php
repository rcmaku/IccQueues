<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT') <!-- This tells Laravel to treat the request as PUT -->

    <div class="mb-4">
        <label for="roleName" class="block text-sm font-medium text-gray-700">Role Name</label>
        <input type="text" name="roleName" id="roleName" class="mt-1 block w-full border-gray-300 rounded-md"
               value="{{ old('roleName', $role->roleName) }}" required>
    </div>

    <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
            <option value="1" @selected($role->status == 1)>Active</option>
            <option value="0" @selected($role->status == 0)>Inactive</option>
        </select>
    </div>

    <div class="mb-6">
        <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            Update Role
        </button>
    </div>
</form>
