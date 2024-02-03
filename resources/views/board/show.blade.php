@extends('layouts.app')

@section('section')
<h3>{{ $board->title }}</h3>

<div>
    <div class="thumbnail">
        <span
            style="background-image: url('<?php echo isset($board->user_thumbnail) ? $board->user_thumbnail : '../asset/images/img_user.png'; ?>')"
        ></span>
    </div>
    <p>{{ $board->user_name }}</p>
    <p>{{ $board->created_at }}</p>
</div>
<!-- 더보기 영역 -->
<div>
    <button>
        <span>더보기</span>
        <i></i>
    </button>
    <div>
        <button>신고하기</button>
        <form action="/board/{{ $board->id }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit">삭제하기</button>
        </form>
        <button>
            <span>더보기 팝업 닫기</span>
        </button>
    </div>
</div>
<div>
    <p>
        {!! $board->content !!}
    </p>
</div>
<!-- 리액션 버튼 -->
<div>
    <div></div>
    <button>
        <span></span>
        <span></span>
    </button>
</div>
<!-- 댓글 -->
<!-- 댓글 목록 -->
<!-- 댓글 내용 -->
<!-- 댓글 작성 -->
<!-- 이전글/다음글 -->
<!-- 목록으로 -->
@stop