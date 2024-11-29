<form action="{{ route('agent.update', $agent->id) }}" method="POST" id="agent-form" class="space-y-6">
    @csrf
    @method('PUT') <!-- Use PUT method for updates -->

    <!-- First Name -->
    <div class="mb-4">
        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
        <input type="text" name="first_name" id="first_name"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('first_name') border-red-500 @else border-gray-300 @enderror"
               value="{{ old('first_name', $agent->first_name) }}"
               pattern="^[a-zA-Z\s]+$"
               title="First name can only contain letters and spaces"
               required>
        @error('first_name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Last Name -->
    <div class="mb-4">
        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
        <input type="text" name="last_name" id="last_name"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('last_name') border-red-500 @else border-gray-300 @enderror"
               value="{{ old('last_name', $agent->last_name) }}"
               pattern="^[a-zA-Z\s]+$"
               title="Last name can only contain letters and spaces"
               required>
        @error('last_name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address:</label>
        <input type="email" name="email" id="email"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('email') border-red-500 @else border-gray-300 @enderror"
               value="{{ old('email', $agent->email) }}" required>
        @error('email')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
        <input type="password" name="password" id="password"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('password') border-red-500 @else border-gray-300 @enderror"
               pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
               title="Password must contain at least 8 characters, including uppercase, lowercase, a number, and a special character.">
        @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p id="passwordHelp" class="text-sm text-gray-500 mt-1">Password must contain at least 8 characters, including uppercase, lowercase, a number, and a special character.</p>
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('password_confirmation') border-red-500 @else border-gray-300 @enderror"
               pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$"
               title="Password confirmation must match the password."
               required>
        @error('password_confirmation')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
        <p id="passwordConfirmationHelp" class="text-sm text-gray-500 mt-1">Please confirm your password.</p>
    </div>

    <!-- Agent Order -->
    <div class="mb-4">
        <label for="AgentOrder" class="block text-sm font-medium text-gray-700">Agent Order:</label>
        <input type="number" name="AgentOrder" id="AgentOrder"
               class="form-input mt-1 block w-full border-2 rounded-md shadow-sm p-3
               @error('AgentOrder') border-red-500 @else border-gray-300 @enderror"
               value="{{ old('AgentOrder', $agent->AgentOrder) }}"
               min="1"
               title="Agent Order should be a number greater than or equal to 1."
               required>
        @error('AgentOrder')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Buttons -->
    <div class="flex justify-between mt-6 gap-4">
        <button type="submit" class="bg-blue-600 text-white rounded-lg p-4 w-full flex justify-center items-center text-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
            Submit
        </button>

        <a href="{{ route('agent.index') }}" class="bg-red-600 text-white rounded-lg p-4 w-full flex justify-center items-center text-lg font-semibold hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
            Cancel
        </a>
    </div>
</form>

<script>
    // Form submission check for both Password and Confirm Password fields
    document.getElementById('agent-form').addEventListener('submit', function (event) {
        var password = document.getElementById('password');
        var confirmPassword = document.getElementById('password_confirmation');

        if ((password.value || confirmPassword.value) && (password.value !== confirmPassword.value)) {
            event.preventDefault(); // Prevent form submission
            alert('Passwords do not match. Please ensure both fields are completed.');
        } else if (!password.value || !confirmPassword.value) {
            event.preventDefault(); // Prevent form submission
            alert('Please fill out both password and confirm password fields.');
        }
    });
</script>
