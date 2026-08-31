@extends('layouts.public')

@section('title', 'Application Received | EduBridge Faculty')

@section('content')
    <div class="bg-cream min-h-screen flex items-center justify-center pt-32 pb-24 px-6">
        <div class="max-w-3xl w-full text-center">
            <!-- Premium Success Icon Container -->
            <div class="mb-12 relative inline-block group">
                <!-- Outer Glow -->
                <div class="absolute inset-0 bg-sage rounded-[2.5rem] blur-3xl opacity-20 group-hover:opacity-30 transition-opacity animate-pulse"></div>
                
                <!-- Main Card -->
                <div class="relative z-10 w-32 h-32 bg-warm-white rounded-[2.5rem] border border-border flex items-center justify-center text-sage shadow-2xl shadow-charcoal/5 transform rotate-3 hover:rotate-0 transition-all duration-500">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    
                    <!-- Decorative dots -->
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-gold rounded-full border-4 border-white shadow-sm"></div>
                </div>
            </div>

            <!-- Typography -->
            <h1 class="text-5xl md:text-7xl font-serif font-black text-charcoal mb-8 leading-tight">
                Your Legacy <span class="text-terracotta italic underline decoration-terracotta/10">Starts</span> Here
            </h1>
            
            <p class="text-xl text-muted-foreground font-medium mb-16 leading-relaxed max-w-2xl mx-auto">
                We've received your application to join our global faculty! Our academic board is currently reviewing your expertise. Expect a personal session invite within <span class="text-charcoal font-black border-b-2 border-sage/30">2 business days</span>.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('courses.index') }}" class="w-full sm:w-auto px-12 py-5 bg-terracotta text-white rounded-2xl font-black text-xl shadow-xl shadow-terracotta/20 hover:shadow-terracotta/30 transition-all transform hover:-translate-y-1">
                    Explore Academies
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-12 py-5 bg-warm-white text-charcoal rounded-2xl font-black text-xl border border-border hover:bg-muted transition-all">
                    Return to Portal
                </a>
            </div>

            <!-- Road to Approval - Premium Stepper -->
            <div class="mt-32 pt-16 border-t border-border">
                <p class="text-[10px] font-black text-muted-foreground uppercase tracking-[0.4em] mb-12">Application Lifecycle</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
                    <!-- Connector Line (Desktop) -->
                    <div class="hidden md:block absolute top-10 left-[20%] right-[20%] h-px bg-border z-0"></div>

                    <!-- Step 1: Current -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-14 h-14 bg-terracotta text-white rounded-2xl font-black flex items-center justify-center shadow-xl shadow-terracotta/20 mb-6 group transition-transform hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-charcoal uppercase tracking-widest">Submission</h4>
                        <p class="text-[10px] font-bold text-sage mt-2 bg-sage/10 px-3 py-1 rounded-full uppercase">Completed</p>
                    </div>

                    <!-- Step 2: Next -->
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-14 h-14 bg-warm-white text-muted-foreground rounded-2xl font-black flex items-center justify-center border border-border mb-6 animate-pulse">
                            2
                        </div>
                        <h4 class="text-sm font-black text-charcoal/50 uppercase tracking-widest">Board Review</h4>
                        <p class="text-[10px] font-bold text-muted-foreground/60 mt-2 uppercase">Up Next</p>
                    </div>

                    <!-- Step 3: Final -->
                    <div class="relative z-10 flex flex-col items-center opacity-40">
                        <div class="w-14 h-14 bg-warm-white text-muted-foreground rounded-2xl font-black flex items-center justify-center border border-border mb-6">
                            3
                        </div>
                        <h4 class="text-sm font-black text-charcoal/50 uppercase tracking-widest">Certification</h4>
                        <p class="text-[10px] font-bold text-muted-foreground/60 mt-2 uppercase">Final Step</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
