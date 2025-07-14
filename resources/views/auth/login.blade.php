<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/10 to-purple-50/10"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, rgba(99, 102, 241, 0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(147, 51, 234, 0.1) 0%, transparent 50%);"></div>
        
        <div class="relative min-h-screen flex">
            <!-- Left Side - Marketing Content -->
            <div class="hidden lg:flex lg:flex-1 lg:flex-col lg:justify-center lg:px-12 xl:px-16">
                <div class="max-w-xl">
                    <!-- Logo & Brand -->
                    <div class="flex items-center mb-8">
                        <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl mr-4">
                            <x-application-logo class="h-10 w-10 text-white" />
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold text-white">Venditio</h1>
                            <p class="text-indigo-200 text-lg">Ihr Partner für digitale Rechnungsabwicklung</p>
                        </div>
                    </div>

                    <!-- Main Headline -->
                    <h2 class="text-5xl font-extrabold text-white leading-tight mb-6">
                        Rechnungen 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-300">digital</span> 
                        verwalten
                    </h2>
                    
                    <p class="text-xl text-indigo-100 mb-8 leading-relaxed">
                        Automatisieren Sie Ihre Rechnungsprozesse, sparen Sie Zeit und reduzieren Sie Fehler. 
                        Mit Venditio haben Sie alle Ihre Finanzdokumente im Griff.
                    </p>

                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500/20 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Automatische Verarbeitung</h3>
                                <p class="text-indigo-200">KI-gestützte Rechnungserkennung und -kategorisierung</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-blue-500/20 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Sicher & DSGVO-konform</h3>
                                <p class="text-indigo-200">Höchste Sicherheitsstandards für Ihre Finanzdaten</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-purple-500/20 p-2 rounded-lg">
                                <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Blitzschnell</h3>
                                <p class="text-indigo-200">Ihre Rechnungen in Sekundenschnelle verarbeitet</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-2xl font-bold text-white">98%</div>
                                <div class="text-indigo-200 text-sm">Zeitersparnis</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">10k+</div>
                                <div class="text-indigo-200 text-sm">Zufriedene Kunden</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">24/7</div>
                                <div class="text-indigo-200 text-sm">Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="flex flex-1 flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 lg:flex-none lg:w-96 xl:w-[28rem]">
                <div class="mx-auto w-full max-w-sm lg:max-w-none">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <div class="flex justify-center mb-4">
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-full">
                                <x-application-logo class="h-12 w-12 text-white" />
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">Venditio</h1>
                        <p class="text-indigo-200">Digitale Rechnungsabwicklung</p>
                    </div>

                    <!-- Login Form Card -->
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 p-8 lg:p-10">
                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Willkommen zurück!</h2>
                            <p class="text-gray-600">Melden Sie sich an und starten Sie durch</p>
                        </div>

                        <form class="space-y-6" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    E-Mail Adresse
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white text-gray-900">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Passwort
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200 bg-gray-50 focus:bg-white text-gray-900">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input 
                                        id="remember_me" 
                                        name="remember" 
                                        type="checkbox" 
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                        Angemeldet bleiben
                                    </label>
                                </div>

                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" 
                                       class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-200">
                                        Passwort vergessen?
                                    </a>
                                </div>
                            </div>

                            <!-- Sign In Button -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                    <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                                        <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                    </span>
                                    Jetzt anmelden und durchstarten
                                </button>
                            </div>
                        </form>

                        <!-- Trust Signals -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.012-3.014l.976-1.409a2.036 2.036 0 012.96 2.73L21 12l-9.39 9.39a2 2 0 01-2.828 0L3.172 15.78a4 4 0 010-5.656l6.717-6.717" />
                                    </svg>
                                    SSL-verschlüsselt
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    DSGVO-konform
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-purple-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    24/7 Support
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-8">
                        <p class="text-xs text-white/70">
                            © {{ date('Y') }} Venditio. Revolutionieren Sie Ihre Rechnungsabwicklung.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

