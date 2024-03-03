@extends('layouts.app')

@section('section')

    <form action="{{ route('board_update', ['board_id' => $board->id]) }}" method="post">
        @csrf
        @method('PUT')

        <div>
            <div>
                <!-- 타이틀 -->
                <div>
                    <h2>게시판</h2>
                    <h3>글 수정</h3>
                </div>
                <!-- 제목 -->
                <dl>
                    <dt>제목</dt>
                    <div>
                        <input 
                            type="text"
                            id="title"
                            name="title"
                            value="{{ $board->title }}"
                        />
                    </div>
                </dl>
                <!-- 에디터 -->
                <div>
                    <dt>내용</dt>
                    <textarea 
                        id="content"
                        name="content"
                    >{{ $board->content }}</textarea>
                </div>

        <!-- 수정하기 버튼 -->
        <div>
            <button type="submit">수정하기</button>
        </div>
    </div>
</form>
        

@stop