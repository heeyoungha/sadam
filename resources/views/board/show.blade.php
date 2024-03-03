@extends('layouts.app')

@section('section')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
        padding: 0;
    }

    h3 {
        color: #333;
        margin: 20px 0;
    }

    div.user-info {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    div.user-info div.thumbnail {
        width: 50px;
        height: 50px;
        background-size: cover;
        border-radius: 50%;
        margin-right: 10px;
    }

    div.user-info p {
        margin: 0;
        color: #666;
    }

    button.more-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    div.more-options {
        display: none;
        margin-top: 10px;
    }

    div.more-options button {
        display: block;
        margin: 5px 0;
        padding: 5px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    div.content p {
        color: #333;
        margin: 0;
    }

    div.reaction-button {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    div.reaction-button div {
        width: 30px;
        height: 30px;
        background-color: #4CAF50;
        border-radius: 50%;
        margin-right: 10px;
    }

    div.reaction-button button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    div.replies h4 {
        color: #333;
        margin-bottom: 10px;
    }

    div.replies ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    div.replies li {
        margin-bottom: 20px;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    div.replies li div {
        display: flex;
        align-items: center;
    }

    div.replies li div div.thumbnail {
        width: 40px;
        height: 40px;
        background-size: cover;
        border-radius: 50%;
        margin-right: 10px;
    }

    div.replies li div div p {
        margin: 0;
        color: #666;
    }

    div.replies li div div button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
        margin-left: auto;
    }

    form.reply-form {
        margin-top: 20px;
    }

    form.reply-form div {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    form.reply-form div textarea {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    form.reply-form div button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
</style>

<h3>{{ $board->title }}</h3>

<div>
    <div>
        <!-- <span
            style="background-image: url('<?php echo isset($board->user_thumbnail) ? $board->user_thumbnail : '../asset/images/img_user.png'; ?>')"
        ></span> -->
    </div>
    <p>{{ $board->user_name }}</p>
    <p>{{ $board->created_at }}</p>
</div>
<!-- 더보기 영역 -->
<div>
    <div>
        <form action="/board/{{ $board->id }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit">삭제하기</button>
        </form>
        <a href="{{ route('board_edit', ['board_id' => $board->id]) }}">
            <button>수정하기</button>
        </a>
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
        <span>좋아요</span>
    </button
</div>
<!-- 댓글 -->
<div>
    <h4><span>{{ $replyCount }}</span>댓글</h4>
    <!-- 댓글 목록 -->
    <div>
        <ul>
            @foreach ($replies as $index => $item)
            <li>
                <!-- 댓글 내용 -->
                <div>
                    <div>
                        <!-- <span
                            style="background-image: url('<?php echo isset($item->user_thumbnail) ? 
                                        $item->user_thumbnail : 
                                        '../asset/images/img_user.png'; ?>')"    
                        ></span> -->
                    </div>
                    <div>
                        <div>
                            <p>{{ $item['user_name'] }}</p>
                            <p>{{ $item['created_at'] }}</p>
                            <div id="reply" data-idx="{{ $item->id }}"></div>
                            <button>
                                <span>좋아요</span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <p>{{ $item['content'] }}</p>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
<!-- 댓글 작성 -->
<form method="post" action="{{ route('replys_store',['board_id' => $board->id]) }}" onsubmit="return validateForm()">
    @csrf
    <div>
        <div>
            <textarea
                name="content"
                id="content"
                cols="30"
                rows="10"
                placeholder=""
            ></textarea>
        </div>
        <button>댓글달기</button>
    </div>
</form>
<!-- 이전글/다음글 -->
<!-- 목록으로 -->

<script>
    function validateForm(){
        var content = document.getElementById('content').value;

        if(content.trim() === ''){
            alert('dd');
            return false;
        }
        return true;
    }
</script>

@stop