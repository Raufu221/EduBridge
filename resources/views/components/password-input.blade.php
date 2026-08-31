@props(['disabled' => false, 'name' => '', 'placeholder' => '••••••••'])

<div x-data="{ show: false }" class="w-full relative">
    <input 
        {{ $disabled ? 'disabled' : '' }} 
        {!! $attributes->merge(['class' => 'w-full rounded-2xl border-gray-100 bg-gray-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3.5 font-bold text-gray-700 shadow-sm transition-all pr-12']) !!}
        :type="show ? 'text' : 'password'"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
    >
    <button 
        type="button" 
        @click="show = !show" 
        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition-colors focus:outline-none"
        :aria-label="show ? 'Hide password' : 'Show password'"
    >
        <!-- Eye Icon (Show) -->
        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        <!-- Eye-Slash Icon (Hide) -->
        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
        </svg>
    </button>
</div>
