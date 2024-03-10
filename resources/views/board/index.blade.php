@extends('layouts.app') 
@section('section')
<section class="py-5">
    <div class="container">
        <h1>게시판</h1>
    </div>
</section>

<form action="{{ route('board.index') }}" method="GET" id="search_form" class="mb-4">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <select name="filter" class="form-select">
                    <option value="title" {{ $filter === 'title' ? 'selected' : null }}>제목</option>
                    <option value="content" {{ $filter === 'contents' ? 'selected' : null }}>내용</option>
                    <option value="writer" {{ $filter === 'writer' ? 'selected' : null }}>작성자</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="" name="searchTitle" placeholder="검색어를 입력하세요" value="{{ $searchTitle }}" class="form-control">
                    <button type="submit" class="btn btn-primary">
                        <span>검색</span>
                    </button>
                </div>
            </div>
        </div>
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
        <caption></caption>
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
@stop
