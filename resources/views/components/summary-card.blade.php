@props(['color' => 'gray', 'icon' => 'info', 'label' => '', 'value' => 0])

<div class="relative bg-white hover:bg-{{ $color }}-50 transition-all duration-300 rounded-xl p-5 shadow-sm border border-gray-100 group">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium">{{ $label }}</p>
            <h4 class="text-2xl font-bold text-{{ $color }}-600 mt-1">{{ $value }}</h4>
        </div>
        <span class="material-symbols-outlined text-{{ $color }}-500 text-3xl group-hover:scale-110 transition-transform">{{ $icon }}</span>
    </div>
</div>
