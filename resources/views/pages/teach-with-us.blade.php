<x-app-layout>
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Inspire the World. <span class="text-indigo-600">Teach on EduBridge.</span></h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Join thousands of passionate educators sharing their knowledge, building their personal brand, and earning a living doing what they love.</p>
        </div>

        <!-- Success or Error Messages -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-200 shadow-sm flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if(isset($existingApplication))
                <!-- User has already applied -->
                <div class="p-16 text-center text-gray-600">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-indigo-50 text-indigo-400 mb-6">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Application Under Review</h2>
                    <p class="text-lg mb-8 max-w-md mx-auto">Thank you for applying! Our admin team is currently reviewing your application. We will reach out soon.</p>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-3.5 bg-gray-900 text-white rounded-xl font-bold text-lg hover:bg-black transition shadow-sm">
                        Return to Dashboard
                    </a>
                </div>
            @else
                <!-- Application Form -->
                <form action="{{ route('teach.store') }}" method="POST" class="p-8 sm:p-12 text-left">
                    @csrf
                    
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Tell us about yourself</h3>
                            <p class="text-base text-gray-500 mb-8 border-b border-gray-100 pb-8">This helps our team understand your expertise and teaching style.</p>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Your Teaching Bio <span class="text-rose-500">*</span></label>
                                    <textarea name="bio" rows="6" required minlength="50" placeholder="I am a senior software engineer with 10 years of experience building enterprise web applications. I love breaking down complex topics into simple blueprints..." class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 transition text-gray-900 resize-none">{{ old('bio') }}</textarea>
                                    <p class="text-xs text-gray-500 mt-2 font-medium">Please provide a minimum of 50 characters.</p>
                                    @error('bio') <p class="text-rose-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Portfolio or LinkedIn URL <span class="text-gray-400 font-normal">(Optional)</span></label>
                                    <input type="url" name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://linkedin.com/in/yourprofile" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none bg-gray-50 transition text-gray-900">
                                    @error('portfolio_url') <p class="text-rose-500 text-sm mt-1 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-8 mt-8 border-t border-gray-100 text-center">
                            <button type="submit" class="w-full sm:w-auto sm:min-w-[300px] inline-flex items-center justify-center gap-2 py-4 px-8 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition shadow-md hover:shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Submit Application
                            </button>
                            <p class="text-xs text-gray-400 mt-4">By submitting this application, you agree to our Terms of Service.</p>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
