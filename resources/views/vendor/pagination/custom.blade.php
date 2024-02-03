@if ($paginator->hasPages())
    <a> href="{{ $paginator->previousPageUrl() }}"<span>이전/span></a>
    @php
        $start = @intval($paginator->currentPage()/10);
        $start = ($start*10)+1;
        $end = $start+10;
        if($end > $paginator->lastPage()){
            $end = $paginator->lastPage()+1;
        }
    @endphp
    <ul>
        @for($i=$start ;$i < $end; $i++)
            @if ($i == $paginator->currentPage())
                <li><a href="#">{{ $i }}</a></li>
            @else
                <li><a href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor
    </ul>
    <a href="{{ $paginator->nextPageUrl()}} "><span>다음</span></a>
@else
    <li><a href="#">1</a></li>
    <a href="{{ $paginator->nextPageUrl()}} "><span>다음</span></a>
@endif