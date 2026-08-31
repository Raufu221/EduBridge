<x-app-layout>
    <div class="min-h-screen bg-cream pb-12" x-data="{ activeTab: 'profile' }">
        
        <!-- 1. MODERN HEADER -->
        <div class="bg-charcoal pt-12 pb-24 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-indigo-500/10 to-transparent"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight">
                            Account Settings
                        </h1>
                        <p class="text-white/70 mt-2 text-sm font-medium">
                            Manage your profile information and account security.
                        </p>
                    </div>
                </div>

                <!-- TABS -->
                <div class="flex items-center gap-2 mt-12 overflow-x-auto pb-2 scrollbar-hide">
                    <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap">
                        My Profile
                    </button>
                    <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-terracotta text-white shadow-lg shadow-terracotta/20' : 'text-white/60 hover:text-white hover:bg-white/5'" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-all whitespace-nowrap">
                        Security
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. MAIN CONTENT -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- PROFILE TAB -->
            <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <form action="{{ route('learner.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <!-- Profile Picture Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-3xl overflow-hidden border-4 border-white shadow-xl relative bg-gray-50">
                                    <img id="avatar-preview" src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff&size=128' }}" class="w-full h-full object-cover">
                                </div>
                                <label for="profile_pic" class="absolute -bottom-2 -right-2 bg-charcoal text-white p-2.5 rounded-xl cursor-pointer hover:bg-indigo-600 transition shadow-lg border-2 border-white group-hover:scale-110 duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <input type="file" id="profile_pic" name="profile_pic" class="hidden" accept="image/*" onchange="previewImage(event)">
                                </label>
                            </div>
                            <div class="text-center md:text-left">
                                <h3 class="text-xl font-black text-gray-900">Profile Picture</h3>
                                <p class="text-gray-400 text-sm mt-1 font-medium">Upload a high-quality photo. Max size 2MB.</p>
                                <button type="button" onclick="document.getElementById('profile_pic').click()" class="mt-4 px-6 py-2 border-2 border-gray-100 rounded-xl text-xs font-black text-gray-600 hover:bg-gray-50 transition">
                                    Change Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Information Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                            <h3 class="text-lg font-black text-gray-900">Personal Information</h3>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Primary contact details</p>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3.5 font-bold text-gray-700 shadow-sm transition-all" placeholder="John Doe">
                                    @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3.5 font-bold text-gray-700 shadow-sm transition-all" placeholder="john@example.com">
                                    @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number</label>
                                    <x-phone-input name="phone" value="{{ old('phone', $user->phone) }}" />
                                    @error('phone') <p class="text-red-500 text-[10px] mt-1 font-bold">Phone number must be exactly 11 digits</p> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Joined Edubridge</label>
                                    <input type="text" value="{{ $user->created_at->format('M d, Y') }}" class="w-full rounded-2xl border-gray-100 bg-gray-100/50 cursor-not-allowed py-3.5 font-bold text-gray-400 shadow-sm" readonly disabled>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Bio / Headline</label>
                                <textarea name="about_me" rows="3" class="w-full rounded-2xl border-gray-100 bg-gray-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3.5 font-bold text-gray-700 shadow-sm transition-all" placeholder="Passionate learner, aspiring developer...">{{ old('about_me', $user->about_me) }}</textarea>
                            </div>
                        </div>
                        <div class="p-8 bg-gray-50/30 border-t border-gray-50 flex justify-end">
                            <button type="submit" class="px-10 py-4 bg-charcoal text-white rounded-2xl font-black text-sm hover:bg-indigo-600 transition shadow-xl shadow-charcoal/10 hover:-translate-y-1 transform duration-300">
                                Save Profile Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- SECURITY TAB -->
            <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                <form action="{{ route('learner.settings.password') }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    @csrf
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <h3 class="text-lg font-black text-gray-900">Update Password</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Secure your account</p>
                    </div>
                    <div class="p-8 space-y-6 max-w-lg">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Current Password</label>
                            <x-password-input name="current_password" />
                            @error('current_password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">New Password</label>
                            <x-password-input name="password" />
                            @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                            <x-password-input name="password_confirmation" />
                        </div>
                    </div>
                    <div class="p-8 bg-gray-50/30 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="px-10 py-4 bg-terracotta text-white rounded-2xl font-black text-sm hover:bg-terracotta/90 transition shadow-xl shadow-terracotta/10 hover:-translate-y-1 transform duration-300">
                            Update Security
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatar-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>
