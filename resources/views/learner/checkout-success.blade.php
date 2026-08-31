<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-4">
            
            <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 text-center relative overflow-hidden">
                <!-- Background Decoration -->
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl"></div>

                @if($method === 'stripe' || $method === 'sslcommerz')
                    <!-- Stripe Success UI -->
                    <div class="relative">
                        <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-emerald-500/20">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Payment Received!</h1>
                        <p class="text-gray-500 font-medium mb-10 leading-relaxed">Your transaction was successful. You now have full access to <span class="text-indigo-600 font-black">{{ $course->title }}</span>.</p>
                        
                        <a href="{{ route('learner.course.viewer', $course->id) }}" class="block w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">
                            Start Learning Now
                        </a>
                    </div>
                @else
                    <!-- Manual Pending UI -->
                    <div class="relative">
                        <div class="w-20 h-20 bg-amber-400 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-amber-400/20">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h1 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">TrxID Submitted!</h1>
                        <p class="text-gray-500 font-medium mb-10 leading-relaxed">We have received your manual payment details. Our admin team will verify the transaction within <span class="text-red-600 font-black">24 hours</span>.</p>
                        
                        <div class="space-y-4">
                            <a href="{{ route('learner.dashboard') }}" class="block w-full py-4 bg-gray-900 text-white rounded-2xl font-black text-sm hover:bg-gray-800 transition">
                                Go to Dashboard
                            </a>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Verification Status: Pending</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-8 text-center">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Need help? Contact support@edubridge.com</p>
            </div>
        </div>
    </div>
</x-app-layout>
