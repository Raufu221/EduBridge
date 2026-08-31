@extends('layouts.public')

@section('title', 'Our Experts | Industry-Leading Faculty | EduBridge')

@section('content')
<div class="bg-slate-50 min-h-screen -mt-24">
    
    <!-- Top Header Section (Seamless with Navbar) -->
    <div class="bg-white border-b border-gray-100 pt-32 pb-16 lg:pb-24">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-50 rounded-full border border-orange-100 mb-8">
                <span class="w-2 h-2 rounded-full bg-orange-600 animate-pulse"></span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-orange-600">Global Faculty</span>
            </div>
            <h1 class="text-5xl lg:text-7xl font-serif font-black text-stone-900 tracking-tight mb-6">Meet the Experts</h1>
            <p class="text-xl text-stone-500 font-medium max-w-2xl mx-auto leading-relaxed">
                Discover the practitioners and visionaries who are defining the next decade of industry excellence.
            </p>
        </div>
    </div>

    <!-- Experts Grid Section -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 py-16 lg:py-24">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 lg:gap-12">
            @foreach($instructors as $instructor)
            <div class="group relative flex flex-col items-center">
                <!-- Expert Card / Image Container -->
                <a href="{{ route('instructor.profile', $instructor) }}" class="relative mb-8 block">
                    <!-- Glowing Background Glow -->
                    <div class="absolute inset-0 bg-orange-600 rounded-full blur-3xl opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                    
                    <!-- Profile Image -->
                    <div class="relative w-56 h-56 lg:w-64 lg:h-64 rounded-full border-4 border-white shadow-2xl overflow-hidden group-hover:scale-105 transition-transform duration-500 ring-1 ring-gray-100">
                        <img src="{{ $instructor->profile_pic ? asset('storage/' . $instructor->profile_pic) : 'https://ui-avatars.com/api/?name='.urlencode($instructor->name).'&color=EA580C&background=FFF7ED&size=512' }}" 
                             alt="{{ $instructor->name }}" 
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                        
                        <!-- Course Count Badge -->
                        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <span class="bg-white/95 backdrop-blur-sm px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest text-stone-900 shadow-xl border border-gray-100 whitespace-nowrap">
                                {{ $instructor->courses_count }} Academies
                            </span>
                        </div>
                    </div>

                    <!-- Verified Icon Badge -->
                    <div class="absolute bottom-4 right-4 bg-white p-2 rounded-full shadow-lg border border-gray-50 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    </div>
                </a>
                
                <!-- Content -->
                <div class="text-center">
                    <h4 class="text-2xl font-bold text-stone-900 mb-1 group-hover:text-orange-600 transition-colors">
                        {{ $instructor->name }}
                    </h4>
                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-stone-400 mb-6">
                        {{ $instructor->expertise ?? 'Lead Industry Mentor' }}
                    </p>
                    
                    <a href="{{ route('instructor.profile', $instructor) }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-stone-400 group-hover:text-stone-900 transition-all">
                        View Repository
                        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Premium Pagination -->
        @if($instructors->hasPages())
        <div class="mt-24 flex justify-center">
            {{ $instructors->links() }}
        </div>
        @endif
    </div>

    <!-- Final CTA Section (Faculty Style) -->
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10 pb-24">
        <div class="bg-stone-900 rounded-[3rem] p-12 lg:p-20 text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-transparent"></div>
            <h2 class="text-4xl lg:text-6xl font-serif font-black text-white mb-8 relative z-10">
                Share your craft <br> with the <span class="text-orange-600">world.</span>
            </h2>
            <p class="text-xl text-stone-400 font-medium mb-12 max-w-xl mx-auto relative z-10">
                Join our elite circle of industry practitioners and help build the next generation of talent.
            </p>
            <a href="{{ route('teach.index') }}" class="relative z-10 inline-flex px-12 py-5 bg-orange-600 text-white rounded-2xl font-black text-xl hover:bg-orange-700 hover:shadow-2xl hover:shadow-orange-600/20 transition-all transform hover:-translate-y-1">
                Apply to Teach
            </a>
        </div>
    </div>
</div>
@endsection
