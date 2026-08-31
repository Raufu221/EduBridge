<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-20 flex flex-col items-center px-4">
        <div class="max-w-3xl w-full">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-gray-900 mb-2">Certificate Verification</h1>
                <p class="text-gray-500 font-medium">Official credential validation portal for EduBridge.</p>
            </div>

            @if($certificate)
                <div class="bg-white rounded-[2rem] shadow-2xl shadow-indigo-100 overflow-hidden border border-gray-100 transition-all transform hover:scale-[1.01]">
                    <!-- Status Header -->
                    <div class="p-8 text-center bg-gradient-to-br {{ $certificate->is_valid ? 'from-emerald-50 to-emerald-100' : 'from-red-50 to-red-100' }}">
                        @if($certificate->is_valid)
                            <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 border-8 border-white shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h2 class="text-2xl font-black text-emerald-900">✅ Verified Authentic</h2>
                            <p class="text-emerald-700 font-bold text-sm uppercase tracking-widest mt-1">Credential ID: {{ $certificate->certificate_code }}</p>
                        @else
                            <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4 border-8 border-white shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <h2 class="text-2xl font-black text-red-900">❌ Certificate Revoked</h2>
                            <p class="text-red-700 font-bold text-sm uppercase tracking-widest mt-1">Status: No longer valid</p>
                        @endif
                    </div>

                    <!-- Details Body -->
                    <div class="p-10">
                        <div class="grid md:grid-cols-2 gap-10">
                            <div>
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Student Name</h3>
                                <p class="text-2xl font-bold text-gray-900">{{ $certificate->full_name }}</p>
                                
                                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mt-8 mb-2">Course Completed</h3>
                                <p class="text-xl font-bold text-indigo-600 leading-tight">{{ $certificate->course->title }}</p>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Issue Date</h3>
                                    <p class="text-lg font-bold text-gray-800">{{ $certificate->issue_date->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Academic Result</h3>
                                    <p class="text-lg font-bold text-gray-800">{{ round($certificate->average_score, 1) }}% Cumulative Average</p>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Instructor</h3>
                                    <p class="text-lg font-bold text-gray-800">{{ $certificate->course->instructor->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="text-sm text-gray-400 font-medium">
                                Digitally signed and timestamped. Verified by EduBridge LMS.
                            </div>
                            <a href="{{ route('courses.show', $certificate->course->slug) }}" class="text-indigo-600 font-black hover:text-indigo-700 flex items-center gap-2 transition group">
                                View Course Details
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-[2rem] p-12 text-center shadow-xl border border-gray-100">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 mb-2">Invalid Certificate Code</h2>
                    <p class="text-gray-500 mb-8 font-medium">The certificate code you entered does not match our records. It may be mistyped or fraudulent.</p>
                    <a href="/" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-800 transition">Return to Homepage</a>
                </div>
            @endif

            <div class="mt-16 text-center text-gray-400 text-xs font-bold uppercase tracking-[0.2em]">
                &copy; {{ date('Y') }} EduBridge Learning Management System
            </div>
        </div>
    </div>
</x-app-layout>
