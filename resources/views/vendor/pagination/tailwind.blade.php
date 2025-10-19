@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center space-x-1 mt-6 select-none">
        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-400">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="flex items-center justify-center w-9 h-9 rounded-full bg-white border text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-400">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-emerald-500 text-white font-medium shadow">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-white border text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="flex items-center justify-center w-9 h-9 rounded-full bg-white border text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </a>
        @else
            <span class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-400">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
