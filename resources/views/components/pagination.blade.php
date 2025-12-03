@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple/20 text-universo-purple/50 border border-universo-purple/30 cursor-not-allowed rounded-md">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple text-white border border-universo-purple rounded-md hover:bg-universo-purple/80 transition-all">
                    Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium bg-universo-purple text-white border border-universo-purple rounded-md hover:bg-universo-purple/80 transition-all">
                    Siguiente
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium bg-universo-purple/20 text-universo-purple/50 border border-universo-purple/30 cursor-not-allowed rounded-md">
                    Siguiente
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-universo-text-muted">
                    Mostrando
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md gap-2">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple/20 text-universo-purple/50 border border-universo-purple/30 cursor-not-allowed rounded-md">
                            Anterior
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple text-white border border-universo-purple rounded-md hover:bg-universo-purple/80 transition-all hover:scale-105">
                            Anterior
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-universo-text-muted">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple text-white border border-universo-purple rounded-md">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-card-bg text-universo-text border border-universo-border rounded-md hover:bg-universo-purple hover:text-white transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple text-white border border-universo-purple rounded-md hover:bg-universo-purple/80 transition-all hover:scale-105">
                            Siguiente
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium bg-universo-purple/20 text-universo-purple/50 border border-universo-purple/30 cursor-not-allowed rounded-md">
                            Siguiente
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif