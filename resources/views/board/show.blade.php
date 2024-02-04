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
                        <span
                            style="background-image: url('<?php echo isset($item->user_thumbnail) ? 
                                        $item->user_thumbnail : 
                                        '../asset/images/img_user.png'; ?>')"    
                        ></span>
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
                            <button>
                                <span>싫어요</span>
                                <span>싫어요</span>
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