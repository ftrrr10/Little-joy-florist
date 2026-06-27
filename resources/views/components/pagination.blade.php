@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex flex-wrap items-center justify-center gap-1.5" aria-label="Navigasi Halaman">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3.5 py-2 text-xs font-bold text-brandText-muted/40 bg-brandSurface-low border border-brandOutline-soft/20 rounded-lg cursor-not-allowed select-none">
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3.5 py-2 text-xs font-bold border rounded-lg transition-all duration-200 border-brandOutline-soft/20 bg-white text-brandText-muted hover:bg-brandSurface-low hover:text-primary hover:border-primary">
                Sebelumnya
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($paginator->links()->elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3.5 py-2 text-xs font-bold text-brandText-muted/40 bg-brandSurface-low border border-brandOutline-soft/20 rounded-lg cursor-not-allowed select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3.5 py-2 text-xs font-bold border rounded-lg transition-all duration-200 bg-primary border-primary text-white shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 text-xs font-bold border rounded-lg transition-all duration-200 border-brandOutline-soft/20 bg-white text-brandText-muted hover:bg-brandSurface-low hover:text-primary hover:border-primary">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3.5 py-2 text-xs font-bold border rounded-lg transition-all duration-200 border-brandOutline-soft/20 bg-white text-brandText-muted hover:bg-brandSurface-low hover:text-primary hover:border-primary">
                Berikutnya
            </a>
        @else
            <span class="px-3.5 py-2 text-xs font-bold text-brandText-muted/40 bg-brandSurface-low border border-brandOutline-soft/20 rounded-lg cursor-not-allowed select-none">
                Berikutnya
            </span>
        @endif
    </nav>
@endif
