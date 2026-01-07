<!-- Footer -->
<footer class="bg-gray-900 text-gray-300 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 text-center">
            
            <div>
                
            </div>
            <div>
                <ul class="space-y-2 text-sm">
                    <!--<li><a href="#features" class="hover:text-white transition-colors">Features</a></li>-->
                    <li><a href="{{ route('cookies') }}" class="hover:text-white transition-colors">Cookies</a></li>
                    <!--<li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Über uns</a></li>-->
                    <!--<li><a href="#" class="hover:text-white transition-colors">Preise</a></li>-->
                    <!--<li><a href="#" class="hover:text-white transition-colors">Dokumentation</a></li>-->
                </ul>
            </div>
            <div>
                
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('impressum') }}" class="hover:text-white transition-colors">Impressum</a></li>
                </ul>
            </div>
            <div>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Datenschutz</a></li>
                    
                </ul>
            </div>
            <div>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Alle Rechte vorbehalten.</p>
        </div>
    </div>
</footer>

