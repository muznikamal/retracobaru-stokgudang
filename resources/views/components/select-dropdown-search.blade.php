{{-- resources/views/components/select-dropdown-search.blade.php --}}
@props(['name', 'label', 'options', 'selected' => null, 'placeholder' => 'Semua'])

<div x-data="{
    open: false,
    selected: @if($selected) '{{ $selected }}' @else '' @endif,
    search: '',
    options: {{ json_encode($options) }},
    
    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(option => 
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    
    selectOption(value) {
        this.selected = value;
        this.open = false;
        this.search = '';
    },
    
    clearSelection() {
        this.selected = '';
        this.open = false;
    }
}" class="relative">
    <label class="font-medium text-gray-700">{{ $label }}</label>
    <div class="relative mt-1">
        {{-- Input hidden untuk form --}}
        <input type="hidden" name="{{ $name }}" x-model="selected">
        
        {{-- Tombol trigger --}}
        <button type="button" 
            @click="open = !open"
            class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white cursor-pointer 
               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 hover:border-emerald-400 transition duration-150 ease-in-out">
            <span x-text="selected ? options.find(opt => opt.value === selected)?.label : '{{ $placeholder }}'" 
                  :class="{ 'text-gray-400': !selected }"></span>
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Dropdown menu dengan search --}}
        <div x-show="open" 
             x-transition
             @click.away="open = false"
             class="absolute z-10 w-full mt-1 bg-white border rounded-xl shadow-lg max-h-60 overflow-auto">
            
            {{-- Search input --}}
            <div class="sticky top-0 bg-white border-b p-2">
                <input type="text" 
                       x-model="search"
                       @click.stop
                       placeholder="Cari..."
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            
            {{-- Options list --}}
            <div class="py-1">
                <template x-if="filteredOptions.length === 0">
                    <div class="px-3 py-2 text-sm text-gray-500">Tidak ada hasil</div>
                </template>
                
                <template x-for="option in filteredOptions" :key="option.value">
                    <button type="button"
                            @click="selectOption(option.value)"
                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 focus:bg-gray-100"
                            :class="{ 'bg-emerald-50 text-emerald-700': selected === option.value }">
                        <span x-text="option.label"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</div>