
@if ($paginator->hasPages())
<div class="d-flex justify-content-between flex-fill d-sm-none">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <a class="page-link">@lang('pagination.previous')</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <a class="page-link">@lang('pagination.next')</a>
            </li>
        @endif
    </ul>
</div>
<div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
    <ul class="pagination" style="overflow-x: auto">
        {{-- Previous Page Link --}}

        <li class="page-item">
            <a class="page-link" @if (!$paginator->onFirstPage()) href="{{ $paginator->previousPageUrl() }}" @endif rel="prev" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                </span>
                <span class="sr-only">Previous</span>
            </a>
        </li>


        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true" >
                    <a class="page-link" >
                        {{ $element }}
                    </a>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li class="page-item" aria-current="page" >
                        <a class="page-link" href="{{$url}}"
                                @if ($page == $paginator->currentPage()) style="background-color: #e9ecef" @endif>
                            {{ $page }}
                        </a>
                    </li>
                @endforeach
            @endif
        @endforeach


        {{-- Next Page Link --}}
        <li class="page-item">
            <a class="page-link" @if ($paginator->hasMorePages()) href="{{ $paginator->nextPageUrl() }}" @endif rel="next" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">
                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                </span>
                <span class="sr-only">Next</span>
            </a>
        </li>
    </ul>
</div>
@endif
