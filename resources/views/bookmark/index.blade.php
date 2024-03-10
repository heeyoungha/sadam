@extends('layouts.app')

@section('section')

<section class="mb-4">
    <div class="container">
        <h2>북마크 게시글</h2>
    </div>
</section>

<form action="{{ route('bookmark.index') }}" method="GET" id="search_form" class="mb-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="d-flex align-items-center">
                작성자
            </div>
            <div class="">
                <div class="input-group">
                    <input type="text" class="form-control" name="sn" placeholder="작성자를 입력하세요" value="{{ $sn }}">
                    <button type="submit" class="btn btn-primary">검색</button>
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
        <thead>
            <tr>
                <th scope="col">게시글 제목</th>
                <th scope="col">작성자</th>
                <th scope="col">작성일자</th>
                <th scope="col">북마크 횟수</th>
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
                        <td>{{ $item['reaction_count'] }}</td>
                    </tr>
                @endforeach
            @else
                <!-- 게시물이 없을 경우 -->
                <tr>
                    <td colspan="4">
                        등록된 게시물이 없습니다.
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
