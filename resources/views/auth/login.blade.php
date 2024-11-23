<x-layout :isLoginPage="true">
    <x-slot:heading>
        User Login
    </x-slot:heading>

    <div class="min-h-screen flex bg-gray-100">
        <!-- Left Half (Video Background with Text Overlay) -->
        <div class="hidden lg:flex w-1/2 relative">
            <!-- Background Video -->
            <video autoplay muted loop class="absolute inset-0 h-full w-full object-cover">
                <source src="https://videos.pexels.com/video-files/28709421/12457750_1080_1920_30fps.mp4" type="video/mp4">
            </video>

            <!-- Text Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="text-center text-white px-8">
                    <h2 class="text-2xl md:text-4xl font-bold">ICC IT</h2>
                    <p class="mt-4 text-lg">Beta Site</p>
                </div>
            </div>
        </div>

        <!-- Right Half (Login Form) -->
        <div class="flex flex-col justify-center w-full lg:w-1/2 px-8 md:px-16 bg-white">
            <div class="max-w-md mx-auto space-y-8">
                <!-- Logo -->
                <div class="text-center">
                    <img src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=500" alt="Logo" class="h-12 w-auto mx-auto">
                    <h1 class="text-2xl font-bold text-gray-900 mt-4">Inicio Sesión</h1>
                </div>

                <!-- Login Form -->
                <form class="space-y-6" action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div class="rounded-md shadow-sm">
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Ingrese su correo electrónico">
                        </div>
                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="Ingrese su contraseña">
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Recuérdame</label>
                        </div>
                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="text-center text-sm text-gray-600">
                    ¿No tienes cuenta aún?
                    <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Crear Cuenta</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
