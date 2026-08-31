@extends('layouts.public')

@section('title', 'Teach with Us | Join our Expert Faculty | EduBridge')

@section('content')
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, hsl(14, 60%, 94%) 0%, hsl(30, 25%, 97%) 50%, hsl(38, 70%, 92%) 100%);
        }

        .benefit-card {
            background-color: hsl(30, 20%, 99%);
            border: 1px solid hsl(30, 15%, 90%);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .benefit-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            border-color: hsl(14, 70%, 52%);
        }

        .form-input {
            background-color: hsl(30, 20%, 99%);
            border: 1.5px solid hsl(30, 15%, 90%);
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: hsl(14, 70%, 52%);
            box-shadow: 0 0 0 4px hsl(14, 60%, 94%);
        }
    </style>

    <div class="bg-cream min-h-screen">
        <!-- Modern Hero Section -->
        <header class="hero-gradient pt-40 pb-40 relative">
            <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/50 backdrop-blur-md rounded-full border border-white mb-8 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-terracotta animate-pulse"></span>
                    <span class="text-xs font-black uppercase tracking-widest text-terracotta">Join our faculty</span>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-serif font-black text-charcoal leading-[1.1] mb-8">
                    Inspire the <span class="text-terracotta">Next</span> <br>
                    Generation of Minds
                </h1>
                
                <p class="text-xl text-muted-foreground max-w-2xl mx-auto mb-12 font-medium leading-relaxed">
                    Edubridge connects industry veterans with eager learners. Build your teaching brand with premium tools and a global reach.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-5">
                    <a href="#apply" class="px-10 py-5 bg-terracotta text-white rounded-2xl font-black text-lg shadow-xl shadow-terracotta/20 hover:shadow-terracotta/30 transition-all transform hover:-translate-y-1">
                        Apply to Teach
                    </a>
                    <a href="#benefits" class="px-10 py-5 bg-white text-charcoal rounded-2xl font-black text-lg border border-border hover:bg-muted transition-all">
                        Discover the Benefits
                    </a>
                </div>
            </div>
            
            <!-- Abstract background shape -->
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[800px] h-[800px] bg-terracotta/5 rounded-full blur-[120px]"></div>
        </header>

        <!-- Social Proof Grid -->
        <div class="max-w-7xl mx-auto px-6 -mt-16 pb-16 relative z-20">
            <div class="bg-warm-white/80 backdrop-blur-xl border border-border rounded-[2.5rem] p-8 flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl shadow-charcoal/5">
                <div class="flex flex-col items-center md:items-start">
                    <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-3">Empowering Educators from</p>
                    <div class="flex -space-x-3 overflow-hidden">
                        <img class="inline-block h-10 w-10 rounded-full ring-4 ring-white" src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <img class="inline-block h-10 w-10 rounded-full ring-4 ring-white" src="https://images.unsplash.com/photo-1550525811-e5869dd03032?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <img class="inline-block h-10 w-10 rounded-full ring-4 ring-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=facearea&facepad=2.25&w=256&h=256&q=80" alt="">
                        <img class="inline-block h-10 w-10 rounded-full ring-4 ring-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                    </div>
                </div>
                
                <div class="h-px w-full md:w-px md:h-12 bg-border"></div>

                <div class="flex flex-wrap justify-center gap-8 md:gap-12 opacity-30 grayscale contrast-125">
                    <span class="text-2xl font-serif font-black italic tracking-tighter">Adobe</span>
                    <span class="text-2xl font-sans font-black tracking-tighter">stripe</span>
                    <span class="text-2xl font-serif font-black hover:grayscale-0 transition-all">Google</span>
                    <span class="text-2xl font-sans font-black uppercase tracking-widest">Slack</span>
                </div>
            </div>
        </div>

        <!-- Benefits Grid -->
        <section id="benefits" class="max-w-7xl mx-auto px-6 py-24 relative z-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Card 1 -->
                <div class="benefit-card p-10 rounded-[2.5rem] group">
                    <div class="w-16 h-16 bg-terracotta-light rounded-2xl flex items-center justify-center text-terracotta mb-8 group-hover:rotate-6 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Reach Millions</h3>
                    <p class="text-muted-foreground leading-relaxed font-medium">Speak to a worldwide stage of students ready for your unique expertise.</p>
                </div>

                <!-- Card 2 -->
                <div class="benefit-card p-10 rounded-[2.5rem] group border-b-sage/20">
                    <div class="w-16 h-16 bg-sage/10 rounded-2xl flex items-center justify-center text-sage mb-8 group-hover:-rotate-6 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Earn Revenue</h3>
                    <p class="text-muted-foreground leading-relaxed font-medium">Turn your knowledge into a sustainable income stream with secure payouts.</p>
                </div>

                <!-- Card 3 -->
                <div class="benefit-card p-10 rounded-[2.5rem] group">
                    <div class="w-16 h-16 bg-gold/10 rounded-2xl flex items-center justify-center text-gold mb-8 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Teach Anywhere</h3>
                    <p class="text-muted-foreground leading-relaxed font-medium">Your classroom is wherever you are. Global reach from the comfort of home.</p>
                </div>

                <!-- Card 4 -->
                <div class="benefit-card p-10 rounded-[2.5rem] group border-b-terracotta/20">
                    <div class="w-16 h-16 bg-terracotta/10 rounded-2xl flex items-center justify-center text-terracotta mb-8 group-hover:translate-y-1 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Full Support</h3>
                    <p class="text-muted-foreground leading-relaxed font-medium">Our production and marketing kits make launching a course effortless.</p>
                </div>
            </div>
        </section>

        <!-- Application Form Container -->
        <section id="apply" class="max-w-5xl mx-auto px-6 py-32">
            <div class="bg-warm-white rounded-[3rem] border border-border shadow-2xl shadow-charcoal/5 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-5">
                    <div class="md:col-span-2 bg-charcoal p-12 text-white flex flex-col justify-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-terracotta/10 rounded-full blur-2xl -mr-16 -mt-16"></div>
                        <h2 class="text-4xl font-serif font-black mb-6 leading-tight relative z-10">Start Your <span class="text-terracotta">Legacy</span></h2>
                        <p class="text-cream/60 mb-8 font-medium relative z-10 leading-relaxed">Tell us about your background and what you're passionate about teaching. We usually response within 24-48 hours.</p>
                        
                        <div class="space-y-6 relative z-10">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-terracotta"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                <span class="text-sm font-bold">Expert-Level Reviews</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-terracotta"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                <span class="text-sm font-bold">Production Kits Provided</span>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3 p-12 md:p-16">
                        @if($existingApplication)
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-sage/10 text-sage rounded-[2rem] flex items-center justify-center mx-auto mb-8 border border-sage/20 shadow-xl shadow-sage/5">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-3xl font-black text-charcoal mb-4">Under Review</h3>
                                <p class="text-muted-foreground font-medium mb-8">We've received your application! Our academic board is currently reviewing your profile.</p>
                                <a href="{{ route('dashboard') }}" class="inline-flex px-10 py-5 bg-charcoal text-white rounded-2xl font-black shadow-xl shadow-charcoal/20 hover:shadow-charcoal/30 transition-all transform hover:-translate-y-1">Return to Dashboard</a>
                            </div>
                        @else
                            <form action="{{ route('teach.store') }}" method="POST" class="space-y-8">
                                @csrf
                                
                                @auth
                                    <div class="bg-terracotta/5 p-6 rounded-[2rem] border border-terracotta/10 flex items-center gap-4">
                                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-terracotta text-xl font-black shadow-sm border border-terracotta/10">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-charcoal">Applying as {{ auth()->user()->name }}</p>
                                            <p class="text-xs text-muted-foreground font-medium">Your account will be automatically linked.</p>
                                        </div>
                                    </div>
                                @endauth

                                @guest
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Full Name</label>
                                            <input type="text" name="full_name" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-bold outline-none" placeholder="Jane Cooper">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Work Email</label>
                                            <input type="email" name="email" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-bold outline-none" placeholder="jane@company.com">
                                        </div>
                                    </div>
                                @endguest

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Expertise Area</label>
                                        <input type="text" name="expertise" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-bold outline-none" placeholder="e.g. Advanced Laravel, UI/UX">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Exp. Years</label>
                                        <input type="number" name="experience_years" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-bold outline-none" placeholder="8">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">LinkedIn Profile</label>
                                        <input type="url" name="linkedin" class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-medium outline-none" placeholder="https://...">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Portfolio (URL)</label>
                                        <input type="url" name="portfolio" class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-medium outline-none" placeholder="https://...">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Course Proposal Title</label>
                                    <input type="text" name="proposal_topic" value="{{ old('proposal_topic') }}" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-bold outline-none" placeholder="What will you teach?">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Demo Class Video Link (YouTube/Google Drive)</label>
                                    <input type="url" name="demo_video_url" value="{{ old('demo_video_url') }}" class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-medium outline-none" placeholder="https://youtube.com/watch?v=...">
                                    <p class="text-[10px] text-muted-foreground ml-1">A short 2-3 minute clip of you explaining a concept helps us fast-track your approval.</p>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground ml-1">Teaching Approach</label>
                                    <textarea name="teaching_approach" rows="4" required class="w-full px-6 py-4 form-input rounded-2xl text-charcoal font-medium outline-none" placeholder="Explain your methodology...">{{ old('teaching_approach') }}</textarea>
                                </div>

                                <button type="submit" class="w-full py-5 bg-terracotta text-white rounded-2xl font-black text-xl shadow-xl shadow-terracotta/20 hover:shadow-terracotta/30 transition-all transform hover:-translate-y-1 active:scale-95">Submit My Application</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
