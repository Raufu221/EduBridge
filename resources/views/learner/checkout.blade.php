<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ activeTab: 'card' }">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-10 text-center">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Secure Checkout</h1>
                <p class="text-gray-500 mt-2 font-medium">Complete your enrollment in <span class="text-indigo-600 font-bold">{{ $course->title }}</span></p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Payment Options -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Tab Switcher -->
                    <div class="flex p-1.5 bg-gray-200/50 rounded-2xl">
                        <button @click="activeTab = 'card'" 
                                :class="activeTab === 'card' ? 'bg-white text-gray-900 shadow-md' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-3 text-sm font-black rounded-xl transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Credit/Debit Card
                        </button>
                        <button @click="activeTab = 'local'" 
                                :class="activeTab === 'local' ? 'bg-white text-gray-900 shadow-md' : 'text-gray-500 hover:text-gray-700'"
                                class="flex-1 py-3 text-sm font-black rounded-xl transition duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Mobile Banking / Local Cards
                        </button>
                    </div>

                    <!-- Card Payment Section -->
                    <div x-show="activeTab === 'card'" x-transition class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/50">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-indigo-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-2">Pay with International Cards</h3>
                            <p class="text-gray-500 text-sm mb-8 leading-relaxed">Secure payment via Stripe. We support all major International and local cards. Enrollment is instant after payment.</p>
                            
                            <form action="{{ route('learner.checkout.stripe', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">
                                    Continue to Stripe
                                </button>
                            </form>
                            
                            <div class="mt-6 flex items-center justify-center gap-4 text-gray-400">
                                <span class="text-[10px] font-bold uppercase tracking-widest">Powered By</span>
                                <svg class="h-6 w-auto" viewBox="0 0 60 25" fill="currentColor"><path d="M59.64 14.28c0-4.59-2.24-7.43-5.91-7.43-3.64 0-6 2.82-6 7.42 0 5.46 2.83 7.82 6.54 7.82 1.62 0 3.03-.3 3.99-.81v-3.08c-.9.4-2 .68-2.91.68-1.52 0-2.31-.58-2.38-1.89h9.55c.06-1.11.12-1.84.12-2.71zM52.3 12.3c0-1.1.75-1.74 1.67-1.74.88 0 1.57.64 1.57 1.74H52.3zm-13.84-5.11c-1.2 0-1.84.51-2.22 1v-.81H32v18.78c1.32-.23 2-.7 2.22-1.35v-6.31c.47.41 1.25.75 2.13.75 2.37 0 4.19-1.92 4.19-5.99 0-4.04-1.87-6.07-4.08-6.07zm-.62 8.79c-1.01 0-1.63-.52-1.63-1.62 0-1.07.62-1.59 1.63-1.59 1.05 0 1.52.54 1.52 1.59s-.47 1.62-1.52 1.62zm-12.78-8.79c-1.2 0-1.95.51-2.35 1.05V7.18h-4.38v14.47h4.38v-8.41c0-1.46.72-2.18 1.96-2.18.32 0 .54.04.79.11V7.13c-.15-.06-.4-.14-.4-.14zm-10.91 1.74h-4.22V7.18H1.72V11H0v3.31h1.72v6.62c0 3.4 1.77 4.2 5.09 4.2a12.8 12.8 0 002.3-.23v-3.32c-.37.07-1.14.07-1.55.07-.84 0-1.39-.23-1.39-1.3v-6.04h4.22V10.22z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- SSLCommerz Payment Section -->
                    <div x-show="activeTab === 'local'" x-transition class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/50">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-red-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-2">bKash, Nagad, or Local Cards</h3>
                            <p class="text-gray-500 text-sm mb-8 leading-relaxed">Secure payment via SSLCommerz. We support bKash, Nagad, Rocket, and all Bangladeshi bank cards. Enrollment is instant after payment.</p>
                            
                            <form action="{{ route('learner.checkout.sslcommerz', $course->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-sm hover:bg-red-700 transition shadow-lg shadow-red-600/30">
                                    Pay with SSLCommerz
                                </button>
                            </form>
                            
                            <div class="mt-8 flex items-center justify-center gap-4">
                                <img src="https://securepay.sslcommerz.com/gwprocess/v4/image/SSLCommerz-Pay-With-logo-All-Size-03.png" class="h-10 w-auto grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right: Order Summary -->
                <div class="space-y-6">
                    <div class="bg-[#0f172a] p-8 rounded-[2.5rem] shadow-2xl text-white relative overflow-hidden">
                        <!-- Card Glow -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/20 blur-3xl"></div>
                        
                        <h3 class="text-xs font-black text-indigo-400 uppercase tracking-[0.3em] mb-6">Order Summary</h3>
                        
                        <div class="flex gap-4 mb-8">
                            <div class="w-20 h-14 bg-gray-800 rounded-xl overflow-hidden shadow-inner flex-shrink-0">
                                @if($course->cover_image)
                                    <img src="{{ asset('storage/' . $course->cover_image) }}" class="w-full h-full object-cover opacity-80">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-black line-clamp-2 leading-tight">{{ $course->title }}</h4>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">{{ $course->instructor->name }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-6 border-t border-white/10">
                            <div class="flex justify-between text-sm font-bold text-gray-400">
                                <span>Course Fee</span>
                                <span class="text-white">৳ {{ number_format($course->price) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-bold text-gray-400">
                                <span>Platform Surcharge</span>
                                <span class="text-white">৳ 0.00</span>
                            </div>
                            
                            <div class="pt-4 flex justify-between items-end border-t border-white/10">
                                <span class="text-xs font-black uppercase text-indigo-400 tracking-widest">Total Amount</span>
                                <span class="text-3xl font-black text-white leading-none">৳ {{ number_format($course->price) }}</span>
                            </div>
                        </div>

                        <div class="mt-10 flex items-center gap-3 p-4 bg-white/5 rounded-2xl border border-white/5">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Secured Enrollment</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('courses.show', $course->slug) }}" class="flex items-center justify-center gap-2 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Back to course
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
