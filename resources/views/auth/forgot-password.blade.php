<x-guest-layout>
    <div class="min-h-screen bg-blue-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-block">
                    <div class="flex justify-center mb-4">
                        <div class="bg-white rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow cursor-pointer">
                            <img src="{{ asset('logo/quickBill-Logo-alone.png') }}" alt="quickBill" style="height: 56px; width: auto; object-fit: contain;" />
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-1 hover:text-blue-200 transition-colors">quickBill</h1>
                </a>
            </div>

            <!-- Password Reset Form Card -->
            <div class="bg-white rounded-xl shadow-xl p-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Passwort vergessen?</h2>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-green-800">
                                    {{ __(session('status')) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-5" method="POST" action="{{ route('password.email') }}">
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
                                autofocus
                                placeholder="ihre@email.de"
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition duration-200 bg-gray-50 focus:bg-white text-gray-900 font-medium shadow-sm hover:shadow-md placeholder:text-gray-600">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1">
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-md font-semibold rounded-lg text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 transition duration-200 shadow-md hover:shadow-lg">
                            Passwort zurücksetzen
                        </button>
                    </div>

                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" 
                           class="text-md font-medium text-blue-900 hover:text-blue-800 transition duration-200">
                            Zurück zur Anmeldung
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
