@if ($paginator->hasPages())
    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li>
                <a href="{{ $paginator->url(1) }}" rel="prev">
                &laquo;&laquo;
                </a>
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">
                &laquo;
                </a>
            </li>
        @endif

        <!-- Pagination Elements -->
        @foreach ($elements as $element)
            <!-- "Three Dots" Separator -->
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            <!-- Array Of Links -->
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">
                &raquo;
                </a>
                <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="prev">
                &raquo;&raquo;
                </a>
            </li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
    </ul>
    <input type="hidden" name="requestUrl" value="{{$paginator->path()}}">
    <span style="color: #999;">共
    <em>{{$paginator->lastPage()}}</em>页,
    </span>
    <span style="color: #999;">到第&nbsp<input type="text" style="width: 35px;border: solid 1px #ededed;height: 21px;text-align: center;" value="{{$paginator->query()['page']}}">&nbsp页
    </span>
    <span style="height: 21px;width: 43px;-webkit-border-radius: 2px;-webkit-background-clip: padding-box;-moz-border-radius: 2px;-moz-background-clip: padding;border-radius: 2px;background-clip: padding-box;border: solid 1px #ededed;text-align: center;line-height: 21px;cursor: pointer;" role="button" tabindex="0">确定</span>
@endif
