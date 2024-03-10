@extends('layouts.app')

@section('section')
<div class="container mt-4">
    <h3>{{ $board->title }}</h3>

    <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded shadow-sm mb-4">
        <div class="user-details" style="margin-right: auto;">
            <p>{{ $board->user_name }}</p>
        </div>
        <div class="user-details">
            <p>{{$board->created_at }}</p>
        </div>
        <div class="actions" style="margin-left: auto;">
            @can('delete', $board)
                <form action="/board/{{ $board->id }}" method="post" style="padding: 5px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">삭제하기</button>
                </form>
            @endcan
        </div>
        <div class="actions" >
        @can('update', $board)
            <a href="{{ route('board_edit', ['board_id' => $board->id]) }}" class="btn btn-primary">수정하기</a>
        @endcan
        </div>
    </div>

    <div class="content bg-white p-3 rounded shadow-sm mb-4">
        <p>{!! $board->content !!}</p>
    </div>

    <!-- 리액션 버튼 -->
    <div class="reaction-button mb-4">
        <button id="bookmarkButton" class="btn btn-primary">
            <span>북마크</span>
            <span class="replyReactionBtn badge bg-success" id="boardBookmarkCnt">{{ $boardBookmarkCnt == 0 ? '' : $boardBookmarkCnt }}</span>
        </button>
        <button id="likeButton" class="btn btn-primary">
            <span>좋아요</span>
            <span class="replyReactionBtn badge bg-success" id="boardLikeCnt">{{ $boardLikeCnt == 0 ? '' : $boardLikeCnt }}</span>
        </button>
    </div>

    <!-- 댓글 -->
    <div class="replies">
        <h4><span>{{ $replyCount }}</span>댓글</h4>
        <!-- 댓글 목록 -->
        <div>
            <ul class="list-unstyled">
                @foreach ($replies as $index => $item)
                    <li class="bg-white p-3 rounded shadow-sm mb-4">
                        <!-- 댓글 내용 -->
                        <div class="reply">
                            <div class="d-flex align-items-center">
                                <div class="details" style="margin-right: auto;">
                                    <p>{{ $item['user_name'] }}</p>
                                </div>
                                <div class="details">
                                    <p>{{ $item['created_at'] }}</p>
                                </div>
                                <div class="actions ms-auto" style="margin-left: auto;">
                                    <button class="replyBtn btn btn-primary" data-idx="{{ $item->id }}">
                                        <span>좋아요</span>
                                        <span class="replyReactionBtn badge bg-success"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p>{{ $item['content'] }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- 댓글 작성 -->
    <form method="post" action="{{ route('replys_store',['board_id' => $board->id]) }}" onsubmit="return validateForm()" class="reply-form mb-4">
        @csrf
        <div class="mb-3">
            <textarea name="content" id="content" cols="30" rows="1" class="form-control" placeholder="댓글을 입력하세요"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">댓글달기</button>
    </form>


<!-- 이전글/다음글 -->
<!-- 목록으로 -->

<script>
        $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        $('#bookmarkButton').click(function() {
            
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
                    type: 'bookmark'
                },
                success: function(response) {
                    document.querySelector('#boardBookmarkCnt').innerText = response.bookmarkCnt;
                    document.querySelector('#boardLikeCnt').innerText = response.likeCnt;
                },
                error: function(error) {
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
                    self.find(".replyReactionBtn").text(response.likeCnt);
                },
                error: function(error) {
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
</script>
@stop