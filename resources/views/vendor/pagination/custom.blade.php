@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center" style="gap: 18px; margin-top: 24px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true" aria-label="Anterior">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;border:3px solid #e3e8f0;background:#fff;font-size:2.2rem;color:#b0b0b0;">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior"
                       style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;border:3px solid #1A2E75;background:#fff;font-size:2.2rem;color:#1A2E75;transition:all 0.15s;">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span style="width:64px;height:64px;display:inline-flex;align-items:center;justify-content:center;">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active" aria-current="page">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;background:#1A2E75;color:#fff;font-size:2.2rem;font-weight:700;border:3px solid #1A2E75;">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;border:3px solid #1A2E75;background:#fff;color:#1A2E75;font-size:2.2rem;font-weight:700;transition:all 0.15s;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Siguiente"
                       style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;border:3px solid #1A2E75;background:#fff;font-size:2.2rem;color:#1A2E75;transition:all 0.15s;">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="disabled" aria-disabled="true" aria-label="Siguiente">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:16px;border:3px solid #e3e8f0;background:#fff;font-size:2.2rem;color:#b0b0b0;">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif 