<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen bg-blue-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block">
                    <div class="flex justify-center mb-4">
                        <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow cursor-pointer">
                            <img src="{{ asset('logo/quickBill HauptLogo2.png') }}" alt="quickBill" style="height: 50px; width: auto; object-fit: contain;" />
                        </div>
                    </div>
                    
                </a>
            </div>

            <!-- Login Form Card -->
            <div class="bg-white rounded-xl shadow-xl p-8">
                        <div class="text-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Anmelden</h2>
                        </div>

                        <form class="space-y-5" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-800 mb-2">
                                    E-Mail Adresse
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value="{{ old('email') }}"
                                        autocomplete="email"
                                        required
                                        placeholder="ihre@email.de"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition duration-200 bg-gray-50 focus:bg-white text-gray-900 font-medium shadow-sm hover:shadow-md placeholder:text-gray-600">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-800 mb-2">
                                    Passwort
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        autocomplete="current-password"
                                        required
                                        placeholder="••••••••"
                                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition duration-200 bg-gray-50 focus:bg-white text-gray-900 font-medium shadow-sm hover:shadow-md placeholder:text-gray-600">
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        id="remember_me" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="h-4 w-4 text-blue-900 focus:ring-blue-800 border-gray-300 rounded">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                        Angemeldet bleiben
                                    </label>
                                </div>

                                
                            </div>

                            <!-- Sign In Button -->
                            <div class="pt-1">
                                <button
                                    type="submit"
                                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition duration-200 shadow-md hover:shadow-lg">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                        
                                    </span>
                                    Anmelden
                                </button>
                            </div>
                            <!-- Forgot Password -->
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" 
                                   class="text-md font-medium text-blue-900 hover:text-blue-800 transition duration-200">
                                    Passwort vergessen?
                                </a>
                            </div>
                        </form>

            </div>
        </div>
    </div>
</x-guest-layout>

