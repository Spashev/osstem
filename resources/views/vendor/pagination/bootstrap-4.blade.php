@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span><p class="text-sm text-gray-700 leading-5">
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                </p>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif 
            <li class="active" aria-current="page">
                <span class="page-link ml-2 mr-2">{{ $paginator->currentPage() }}</span>
            </li>
            {{-- Next Page Link --}}
            @if ($paginator->currentPage() != $last_page)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
            <li class="page-item">
                <a class="page-link" href="?page={{$last_page}}">{{$last_page}}</a>
            </li>
        </ul>
    </nav>
@endif
