@props(['disabled' => false, 'value' => '', 'name' => '', 'placeholder' => '01XXXXXXXXX'])

<div x-data="{ 
    phone: '{{ $value }}',
    isValid: true,
    errorMessage: '',
    validate() {
        this.phone = this.phone.replace(/[^0-9]/g, '').substring(0, 11);
        if (this.phone.length === 0) {
            this.isValid = true;
            this.errorMessage = '';
        } else if (this.phone.length < 11) {
            this.isValid = false;
            this.errorMessage = 'Phone number must be exactly 11 digits';
        } else {
            this.isValid = true;
            this.errorMessage = '';
        }
    }
}" class="w-full">
    <div class="relative">
        <input 
            {{ $disabled ? 'disabled' : '' }} 
            {!! $attributes->merge(['class' => 'w-full rounded-2xl border-gray-100 bg-gray-50/30 focus:ring-indigo-500 focus:border-indigo-500 py-3.5 font-bold text-gray-700 shadow-sm transition-all']) !!}
            type="text"
            name="{{ $name }}"
            x-model="phone"
            @input="validate()"
            @blur="validate()"
            placeholder="{{ $placeholder }}"
            maxlength="11"
        >
        <div class="absolute right-4 top-1/2 -translate-y-1/2">
            <template x-if="phone.length === 11 && isValid">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </template>
        </div>
    </div>
    <p x-show="!isValid && phone.length > 0" x-transition x-text="errorMessage" class="text-[10px] text-red-500 font-bold mt-1 ml-1"></p>
</div>
