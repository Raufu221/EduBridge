@extends('layouts.instructor')
@section('title', 'Settings & Profile')

@section('content')
<div x-data="{ tab: '{{ session('password_success') ? 'password' : 'profile' }}' }" class="max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900">Settings & Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your public profile, account security, and preferences.</p>
    </div>

    {{-- Tab Nav --}}
    <div class="flex gap-1 bg-gray-100 rounded-xl p-1 mb-8 w-fit">
        <button @click="tab = 'profile'"
            :class="tab === 'profile' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2 rounded-lg text-sm transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profile
        </button>
        <button @click="tab = 'password'"
            :class="tab === 'password' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2 rounded-lg text-sm transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Password
        </button>
        <button @click="tab = 'notifications'"
            :class="tab === 'notifications' ? 'bg-white shadow text-gray-900 font-semibold' : 'text-gray-500 hover:text-gray-700'"
            class="px-5 py-2 rounded-lg text-sm transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Notifications
        </button>
    </div>

    {{-- ── TAB 1: PROFILE ── --}}
    <div x-show="tab === 'profile'" x-transition>

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('instructor.settings.profile') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Avatar Upload Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 flex items-center gap-6">
                <div x-data="{ preview: null }" class="flex items-center gap-6 w-full">
                    {{-- Avatar Preview --}}
                    <div class="relative shrink-0">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-gray-100">
                            <img id="avatar-preview"
                                 src="{{ $user->profile_pic ? asset('storage/' . $user->profile_pic) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=E8674A&color=fff&bold=true&size=128' }}"
                                 class="w-full h-full object-cover" alt="Avatar">
                        </div>
                        <label for="avatar-input"
                               class="absolute -bottom-2 -right-2 w-8 h-8 bg-[#E8674A] text-white rounded-full flex items-center justify-center shadow cursor-pointer hover:bg-[#d45a3d] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden"
                               onchange="previewAvatar(event)">
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Profile Photo</h3>
                        <p class="text-sm text-gray-500 mt-1">JPG, PNG or WebP · Max 2MB</p>
                        <p class="text-xs text-gray-400 mt-1">Click the camera icon to upload a new photo</p>
                    </div>
                </div>
            </div>

            {{-- Name & Email --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-4">Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8674A]/30 focus:border-[#E8674A] transition @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Phone Number</label>
                        <x-phone-input name="phone" value="{{ old('phone', $user->phone) }}" />
                        @error('phone') <p class="text-red-500 text-xs mt-1">Phone number must be exactly 11 digits</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">About Me / Bio</label>
                    <textarea name="about_me" rows="4" placeholder="Tell your students a bit about yourself, your expertise, and teaching style..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8674A]/30 focus:border-[#E8674A] transition resize-none">{{ old('about_me', $user->about_me) }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-1">Shown on your public instructor profile. Max 1000 characters.</p>
                </div>
            </div>

            {{-- Social Links --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-4">Social & Website Links</h3>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Website / LinkedIn / YouTube URL</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </span>
                        <input type="text" name="social_links" value="{{ old('social_links', $user->social_links) }}"
                               placeholder="https://linkedin.com/in/yourname"
                               class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#E8674A]/30 focus:border-[#E8674A] transition">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Your primary public link (LinkedIn, personal website, YouTube, etc.)</p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-[#E8674A] hover:bg-[#d45a3d] text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-[#E8674A]/20 transition hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Profile
                </button>
            </div>
        </form>
    </div>

    {{-- ── TAB 2: PASSWORD ── --}}
    <div x-show="tab === 'password'" x-transition>

        @if(session('password_success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('password_success') }}
            </div>
        @endif

        <form action="{{ route('instructor.settings.password') }}" method="POST">
            @csrf
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-4">Change Password</h3>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Current Password</label>
                    <x-password-input name="current_password" required autocomplete="current-password" />
                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">New Password</label>
                        <x-password-input name="password" required autocomplete="new-password" />
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <x-password-input name="password_confirmation" required autocomplete="new-password" />
                    </div>
                </div>

                {{-- Password rules hint --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-xs text-gray-500 border border-gray-100">
                    <p class="font-bold text-gray-700 mb-2">Password requirements:</p>
                    <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> At least 8 characters</div>
                    <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Mix of uppercase and lowercase letters</div>
                    <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> At least one number</div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                        class="bg-[#E8674A] hover:bg-[#d45a3d] text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-[#E8674A]/20 transition hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- ── TAB 3: NOTIFICATIONS ── --}}
    <div x-show="tab === 'notifications'" x-transition 
         x-data="{ 
            settings: {{ json_encode($user->notification_settings ?? [
                'enrollment' => true,
                'review' => true,
                'assignment' => true,
                'payout' => true,
                'announcements' => false
            ]) }},
            saving: false,
            async saveSettings() {
                this.saving = true;
                try {
                    const response = await fetch('{{ route('instructor.settings.notifications') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ settings: this.settings })
                    });
                    if (response.ok) {
                        // Optional: show a small toast
                    }
                } catch (e) { console.error(e); }
                finally { this.saving = false; }
            }
         }">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-1 relative">
            {{-- Saving Indicator --}}
            <div x-show="saving" class="absolute top-4 right-6 flex items-center gap-2 text-[10px] font-bold text-indigo-500 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-full border border-indigo-100">
                <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Saving...
            </div>

            <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-4 mb-4">Email Notification Preferences</h3>

            @php
                $notifItems = [
                    ['key' => 'enrollment', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'New student enrollment', 'desc' => 'Get notified when a student enrolls in your course.'],
                    ['key' => 'review', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'label' => 'New review posted', 'desc' => 'When a student leaves a review on your course.'],
                    ['key' => 'assignment', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'label' => 'Assignment submitted', 'desc' => 'When a student submits an assignment for grading.'],
                    ['key' => 'payout', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Payout processed', 'desc' => 'Confirmation when your payout has been sent.'],
                    ['key' => 'announcements', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'label' => 'Platform announcements', 'desc' => 'Important updates and news from EduBridge.'],
                ];
            @endphp

            @foreach($notifItems as $item)
            <div class="flex items-center justify-between py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="w-9 h-9 bg-[#E8674A]/10 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-[#E8674A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $item['label'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item['desc'] }}</p>
                    </div>
                </div>
                {{-- Toggle with AJAX --}}
                <button type="button"
                    @click="settings.{{ $item['key'] }} = !settings.{{ $item['key'] }}; saveSettings()"
                    :class="settings.{{ $item['key'] }} ? 'bg-[#E8674A]' : 'bg-gray-200'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none shrink-0">
                    <span :class="settings.{{ $item['key'] }} ? 'translate-x-6' : 'translate-x-1'"
                          class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
                </button>
            </div>
            @endforeach

            <p class="text-[10px] text-gray-400 pt-4 italic">Changes are saved automatically when toggled.</p>
        </div>
    </div>

</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('avatar-preview').src = e.target.result; };
        reader.readAsDataURL(file);
    }
}
</script>

@endsection
