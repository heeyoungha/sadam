@extends('layouts.app') 
@section('section')
<section class="py-5">
    <div class="container">
        <h1>게시판</h1>
    </div>
</section>

<form action="{{ route('board.index') }}" method="GET" id="search_form" class="mb-4">
    <div class="container mt-3 d-flex gap-2 w-50" style="height: 38px;">
        <select name="sf" class="form-select w-25" id="sf">
            <option value="{{ $sfs[0] }}" {{ $sf === $sfs[0] ? 'selected' : null }}>제목</option>
            <option value="{{ $sfs[1] }}" {{ $sf === $sfs[1] ? 'selected' : null }}>내용</option>
            <option value="{{ $sfs[2] }}" {{ $sf === $sfs[2] ? 'selected' : null }}>작성자</option>
        </select>  
        <input type="text" class="form-control w-50" id="sn" style="height: 38px;" name="sn" placeholder="검색어를 입력하세요" value="{{ $sn }}" class="form-control">
        <button type="submit" class="btn btn-primary" id="btn_search" style="height: 38px;">
            <span>검색</span>
        </button>
    </div>

</form>

<div class="container mb-4">
    <div class="row justify-content-between">
        <div class="text-start">
            <h6>게시글 <span>{{ $countCollection }}</span>개</h6>
        </div>
        <div class="text-end">
            <a href="/board/create" class="btn btn-primary">글쓰기</a>
        </div>
    </div>
</div>

<div class="container">
    <table class="table">
        <thead>
            <tr>
            <th scope="col" style="width: 30%;">제목</th>
            <th scope="col" style="width: 20%;">작성자</th>
            <th scope="col" style="width: 20%;">작성일자</th>
            <th scope="col" style="width: 15%;">조회</th>
            <th scope="col" style="width: 15%;">북마크</th>
            </tr>
        </thead>
        <tbody>
            @if ($countCollection > 0)
                @foreach ($newCollection as $index => $item)
                    <tr>
                        <td>
                            <a href="/board/{{ $item['id'] }}">
                                <p>{{ $item['title'] }}</p>
                                <span></span>
                            </a>
                        </td>
                        <td>{{ $item['uname'] }}</td>
                        <td>{{ $item['formatted_created_at'] }}</td>
                        <td>{{ $item['view_cnt'] }}</td>
                        <td>{{ $item->boardBookmarkReactions()->count() }}</td>
                    </tr>
                @endforeach
            @else
                <!-- 게시물이 없을 경우 -->
                <tr>
                    <td colspan="5">
                        등록된 게시물이 없습니다
                    </td>
                </tr>
            @endif
        </tbody>
        
    </table>
    
</div>

<div style="text-align: center;">
    @if ($newCollection->currentPage() > 1)
        <a href="{{ $newCollection->previousPageUrl() }}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    @endif
    @for($i = 1; $i <= $newCollection->lastPage(); $i++)
        <a href="{{$newCollection->url($i)}}">{{$i}}</a>
    @endfor
    @if ($newCollection->currentPage() < $newCollection->lastPage() )
        <a href="{{$newCollection->nextPageUrl()}}"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
    @endif
</div>

<script>
    function getUrlParams(){
        const params = {};

        window.location.search.replace(/[?&]+([^=&])=([^&]*)/gi,
            function(str, key, value){
                params[key] = value;
            }
        );
    }
    const btn_search = document.querySelector("#btn_search")
    btn_search.addEventListener("click", () => {
        const sn = document.querySelector("#sn")
        const sf = document.querySelector("#sf")
        if(sn.value == ''){
            alert('검색어를 입력해 주세요')
            sn.focus()
            return false
        }
        self.location.href='./board?'+'sn=' + sn.value + '&sf=' +sf.value
    })
</script>
@stop
