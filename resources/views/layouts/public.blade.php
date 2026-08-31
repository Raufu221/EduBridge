<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduBridge') }} | World-Class Learning</title>
    <link rel="icon" href="{{ asset('images/edubridge_icon.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-cream text-charcoal font-sans selection:bg-terracotta selection:text-white">

    <!-- Navigation -->
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'shadow-md border-b border-border': scrolled, 'shadow-sm': !scrolled }"
         class="fixed w-full z-50 transition-all duration-500 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge Icon" class="h-8 w-8 rounded-lg shadow-sm">
                    <span class="text-xl tracking-tight">
                        <span class="font-bold text-charcoal">Edu</span><span class="font-medium text-terracotta">Bridge</span>
                    </span>
                </a>
                
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" 
                       class="text-lg font-bold transition-all {{ request()->routeIs('home') ? 'text-terracotta border-b-2 border-terracotta' : 'text-charcoal hover:text-terracotta' }}">
                        Home
                    </a>
                    <a href="{{ route('courses.index') }}" 
                       class="text-lg font-bold transition-all {{ request()->routeIs('courses.index') || request()->routeIs('courses.show') ? 'text-terracotta border-b-2 border-terracotta' : 'text-charcoal hover:text-terracotta' }}">
                        Catalog
                    </a>
                    <a href="{{ route('instructors.index') }}" 
                       class="text-lg font-bold transition-all {{ request()->routeIs('instructors.index') || request()->routeIs('instructor.profile') ? 'text-terracotta border-b-2 border-terracotta' : 'text-charcoal hover:text-terracotta' }}">
                        Our Experts
                    </a>
                    <a href="{{ route('teach.index') }}" 
                       class="text-lg font-bold transition-all {{ request()->routeIs('teach.index') ? 'text-terracotta border-b-2 border-terracotta' : 'text-charcoal hover:text-terracotta' }}">
                        Teach with Us
                    </a>
                </div>

                <!-- Auth / Actions -->
                <div class="hidden md:flex items-center space-x-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-lg font-bold text-charcoal hover:text-terracotta transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-lg font-bold text-muted-foreground hover:text-terracotta transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-charcoal text-white text-lg font-bold rounded-xl hover:bg-black shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">Sign up</a>
                        @endif
                    @endauth
                </div>
                
                <!-- Mobile button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-charcoal hover:text-terracotta focus:outline-none transition-colors">
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-cloak 
             class="md:hidden bg-white border-t border-border shadow-xl absolute w-full pb-6">
             <div class="px-6 pt-4 pb-3 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('home') ? 'text-terracotta bg-terracotta-light' : 'text-charcoal' }} hover:text-terracotta hover:bg-cream transition-all">Home</a>
                <a href="{{ route('courses.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('courses.index') ? 'text-terracotta bg-terracotta-light' : 'text-charcoal' }} hover:text-terracotta hover:bg-cream transition-all">Explore Catalog</a>
                <a href="{{ route('instructors.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('instructors.index') ? 'text-terracotta bg-terracotta-light' : 'text-charcoal' }} hover:text-terracotta hover:bg-cream transition-all">Our Experts</a>
                <a href="{{ route('teach.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold {{ request()->routeIs('teach.index') ? 'text-terracotta bg-terracotta-light' : 'text-charcoal' }} hover:text-terracotta hover:bg-cream transition-all">Teach with Us</a>
                <div class="border-t border-border pt-6 mt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="block w-full text-center px-6 py-4 bg-terracotta text-white font-black rounded-2xl shadow-lg shadow-terracotta/20">Go to Dashboard</a>
                    @else
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('login') }}" class="flex justify-center px-6 py-4 border border-border rounded-2xl text-base font-bold text-charcoal bg-white hover:bg-cream">Log in</a>
                            <a href="{{ route('register') }}" class="flex justify-center px-6 py-4 bg-charcoal text-white rounded-2xl text-base font-bold hover:bg-black">Sign up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="pt-24">
        @yield('content')
    </main>

    <!-- Premium Footer -->
    <footer class="bg-charcoal text-cream pt-24 pb-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/edubridge_icon.png') }}" alt="EduBridge Icon" class="h-8 w-8 rounded-lg shadow-sm opacity-80">
                        <span class="text-xl tracking-tight">
                            <span class="font-bold text-white">Edu</span><span class="font-medium text-terracotta">Bridge</span>
                        </span>
                    </a>
                    <p class="text-sm text-cream/60 leading-relaxed">
                        Empowering the next generation of industry leaders through peer-to-peer expert learning. Join over 50,000+ students worldwide.
                    </p>
                    <div class="flex gap-4">
                         <!-- Social Placeholders -->
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-terracotta transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-terracotta transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest text-white mb-8">Platform</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('courses.index') }}" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Course Catalog</a></li>
                        <li><a href="{{ route('teach.index') }}" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Become a Teacher</a></li>
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Affiliate Program</a></li>
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Enterprise</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest text-white mb-8">Community</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Student Stories</a></li>
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-cream/70 hover:text-terracotta transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest text-white mb-8">Newsletter</h4>
                    <p class="text-xs text-cream/60 mb-6">Expert tips and curated courses sent weekly. No spam.</p>
                    <form class="flex gap-2">
                        <input type="email" placeholder="Your email" class="flex-1 bg-white/5 border-none rounded-xl text-sm px-4 py-3 placeholder:text-cream/30 focus:ring-1 focus:ring-terracotta">
                        <button class="bg-terracotta px-4 py-3 rounded-xl text-xs font-black uppercase">Join</button>
                    </form>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs text-cream/40 font-medium tracking-tight whitespace-nowrap">
                    &copy; {{ date('Y') }} EduBridge Learning Inc. Crafted for Excellence.
                </p>
                <div class="flex gap-8">
                    <span class="text-[10px] font-black uppercase tracking-widest text-cream/20 italic">Curated by top-tier experts</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
