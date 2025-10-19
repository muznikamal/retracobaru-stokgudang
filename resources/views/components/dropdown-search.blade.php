@props([
    'name' => '',
    'label' => 'Pilih Opsi',
    'options' => [],
    'selectedId' => null,
    'required' => false,
    'placeholder' => 'Cari...',
    'emptyMessage' => 'Tidak ada data ditemukan'
])

<div x-data="dropdownSearch({
    options: {{ Js::from($options) }},
    selectedId: {{ Js::from($selectedId) }},
    initialLabel: '{{ $label }}'
})" 
    {{ $attributes->merge(['class' => 'relative']) }}>
    
    @if($attributes->has('label'))
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $attributes->get('label') }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <!-- Trigger Button -->
    <div @click="open = !open"
        class="flex justify-between items-center w-full border border-gray-300 rounded-lg px-3 py-2 bg-white cursor-pointer shadow-sm hover:border-emerald-400 focus:ring-2 focus:ring-emerald-500 transition">
        <span x-text="selectedName" :class="selectedId ? 'text-gray-700' : 'text-gray-400'"></span>
        <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" 
             :class="{ 'rotate-180': open }" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute z-30 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-hidden">
        
        <!-- Search Box -->
        <div class="sticky top-0 bg-white border-b border-gray-200 p-2">
            <div class="relative">
                <input type="text" 
                       x-model="search"
                       @click.stop
                       placeholder="{{ $placeholder }}"
                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Options List -->
        <div class="max-h-48 overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option.id">
                <div @click="selectOption(option)"
                    class="px-4 py-2 cursor-pointer hover:bg-emerald-100 hover:text-emerald-600 transition"
                    :class="isSelected(option.id) ? 'bg-emerald-50 text-emerald-700 font-medium' : ''"
                    x-text="option.text">
                </div>
            </template>

            <!-- Empty State -->
            <div x-show="filteredOptions.length === 0" 
                 class="px-4 py-3 text-center text-gray-500 text-sm">
                {{ $emptyMessage }}
            </div>
        </div>
    </div>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" :value="selectedId" {{ $required ? 'required' : '' }}>
</div>

<script>
function dropdownSearch(config) {
    return {
        open: false,
        search: '',
        selectedId: config.selectedId,
        selectedName: config.initialLabel,
        options: config.options,
        
        init() {
            // Set selected name jika ada selectedId
            if (this.selectedId) {
                const selected = this.options.find(o => o.id == this.selectedId);
                if (selected) {
                    this.selectedName = selected.text;
                }
            }
        },
        
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(option => 
                option.text.toLowerCase().includes(this.search.toLowerCase())
            );
        },
        
        isSelected(id) {
            return this.selectedId == id;
        },
        
        selectOption(option) {
            this.selectedId = option.id;
            this.selectedName = option.text;
            this.open = false;
            this.search = ''; // Reset search setelah select
            
            // Dispatch event untuk external listeners
            this.$dispatch('dropdown-selected', { 
                id: option.id, 
                text: option.text 
            });
        }
    }
}
</script>