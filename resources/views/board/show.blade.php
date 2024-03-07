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

    .board {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .board img.thumbnail {
        width: 50px;
        height: 50px;
        background-size: cover;
        border-radius: 50%;
        margin-right: 10px;
    }

    .board .user-details {
        flex: 1;
    }

    .board .user-details p {
        margin: 0;
        color: #666;
    }

    .board .actions {
        display: flex;
        gap: 10px;
    }

    .board button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    .content {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .reaction-button {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .reaction-button div {
        width: 30px;
        height: 30px;
        background-color: #4CAF50;
        border-radius: 50%;
        margin-right: 10px;
    }

    .reaction-button button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    .replies h4 {
        color: #333;
        margin-bottom: 10px;
    }

    .replies li {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .replies li img.thumbnail {
        width: 40px;
        height: 40px;
        background-size: cover;
        border-radius: 50%;
        margin-right: 10px;
    }

    .replies li div.details {
        flex: 1;
    }

    .replies li div.details p {
        margin: 0;
        color: #666;
    }

    .replies li div.actions button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
    }

    .reply-form {
        margin-top: 20px;
    }

    .reply-form div {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .reply-form textarea {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .reply-form button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
</style>

<h3>{{ $board->title }}</h3>

<div class="board">
    <div class="user-details">
        <p>{{ $board->user_name }}</p>
        <p>{{ $board->created_at }}</p>
    </div>
    <div class="actions">
        <form action="/board/{{ $board->id }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" id="deleteButton">삭제하기</button>
        </form>
        <a href="{{ route('board_edit', ['board_id' => $board->id]) }}">
            <button id="editButton">수정하기</button>
        </a>
    </div>
</div>

<div class="content">
    <p>{!! $board->content !!}</p>
</div>

<!-- 리액션 버튼 -->
<div class="reaction-button">
    <button id="likeButton">
        <span>좋아요</span>
        <span id="boardReactionCnt">{{$boardReactionCnt}}</span>
    </button>
</div>

<!-- 댓글 -->
<div class="replies">
    <h4><span>{{ $replyCount }}</span>댓글</h4>
    <!-- 댓글 목록 -->
    <div>
        <ul>
            @foreach ($replies as $index => $item)
            <li>
                <!-- 댓글 내용 -->
                <div class="reply">
                    <div class="details">
                        <p>{{ $item['user_name'] }}</p>
                        <p>{{ $item['created_at'] }}</p>
                        <div class="actions">
                            <button class="replyBtn" data-idx="{{ $item->id }}">
                                <span>좋아요</span>
                                <span class="replyReactionBtn"></span>
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
<form method="post" action="{{ route('replys_store',['board_id' => $board->id]) }}" onsubmit="return validateForm()" class="reply-form">
    @csrf
    <div>
        <textarea name="content" id="content" cols="30" rows="10" placeholder=""></textarea>
        <button>댓글달기</button>
    </div>
</form>

<!-- 이전글/다음글 -->
<!-- 목록으로 -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
        $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('#likeButton').click(function() {
            
            var boardId = {{$board->id}};
            var userId = {{$user->id}}
            $.ajax({
                type: 'POST',
                url: '/board/' + boardId + '/reaction', 
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    user_id: userId,
                },
                success: function(response) {
                    document.querySelector('#boardReactionCnt').innerText = response.data.likeCnt;
                },
                error: function(error) {
                    // 에러가 발생한 경우 처리
                    console.log('Error:', error);
                }
            });
        });

        $('.replyBtn').click(function() {
            var boardId = {{$board->id}};
            var userId = {{$user->id}};
            var self = $(this);
            var replyId = self.data("idx");
            

            $.ajax({
                type: 'POST',
                url: '/board/' + boardId + '/reply/'+ replyId +'/reaction', 
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    user_id: userId,
                },
                success: function(response) {
                    self.find(".replyReactionBtn").text(response.data.likeCnt);
                },
                error: function(error) {
                    // 에러가 발생한 경우 처리
                    console.log('Error:', error);
                }
            });
        });
    });
    function validateForm() {
        var content = document.getElementById('content').value;

        if (content.trim() === '') {
            alert('댓글 내용을 입력하세요.');
            return false;
        }
        return true;
    }

    var isPostAuthor = "{{$is_post_author}}";
    document.addEventListener("DOMContentLoaded", function() {
        // 삭제하기 버튼 제어
        var deleteButton = document.getElementById("deleteButton");
        if (isPostAuthor === 'active') {
            deleteButton.style.display = 'flex';
        } else {
            deleteButton.style.display = 'none';
        }

        // 수정하기 버튼 제어
        var editButton = document.getElementById("editButton");
        if (isPostAuthor === 'active') {
            editButton.style.display = 'flex';
        } else {
            editButton.style.display = 'none';
        }
    });
</script>
@stop