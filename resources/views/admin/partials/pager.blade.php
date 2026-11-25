{{-- Pagination footer for admin tables. Expects $paginator. --}}
@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="pager">
        <span>Showing {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} of {{ number_format($paginator->total()) }}</span>
        @if ($paginator->hasPages())
            <div class="pager-links">
                @if ($paginator->onFirstPage())
                    <span class="is-disabled"><x-admin.icon name="arrow-left" size="14" /></span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous page"><x-admin.icon name="arrow-left" size="14" /></a>
                @endif
                @foreach (range(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page)
                    @if ($page === $paginator->currentPage())
                        <span class="is-current">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next page"><x-admin.icon name="arrow-right" size="14" /></a>
                @else
                    <span class="is-disabled"><x-admin.icon name="arrow-right" size="14" /></span>
                @endif
            </div>
        @endif
    </div>
@endif
