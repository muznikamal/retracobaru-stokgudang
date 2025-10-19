@php
    $options = collect($options);
@endphp

<div 
    x-data="{
        open: false,
        selectedValue: '{{ $selected ?? '' }}',
        selectedLabel: '{{ $options->firstWhere('value', $selected)['label'] ?? ($placeholder ?? 'Pilih Opsi') }}',
        options: {{ $options->toJson() }}
    }"
    class="relative"
>
    @if (!empty($label))
        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <!-- Tombol dropdown -->
    <div 
        @click="open = !open"
        class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white cursor-pointer 
               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-emerald-400 transition duration-150 ease-in-out"
    >
        <span x-text="selectedLabel" class="text-gray-700"></span>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <!-- Daftar opsi -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition
        class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
    >
        <template x-for="option in options" :key="option.value">
            <div 
                @click="selectedValue = option.value; selectedLabel = option.label; open = false"
                class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                :class="selectedValue === option.value ? 'bg-emerald-50 text-emerald-700 font-medium' : ''"
                x-text="option.label"
            ></div>
        </template>
    </div>

    <!-- Hidden input untuk form -->
    <input type="hidden" name="{{ $name }}" :value="selectedValue">
</div>
