@extends('layouts.app')

@section('section')
    <div class="container">
        <form action="{{ route('board_update', ['board_id' => $board->id]) }}" method="post">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <h2>게시판</h2>
                <h3>글 수정</h3>
            </div>

            <!-- 제목 -->
            <div class="mb-3">
                <label for="title" class="form-label">제목</label>
                <input 
                    type="text"
                    id="title"
                    name="title"
                    value="{{ $board->title }}"
                    class="form-control"
                />
            </div>

            <!-- 에디터 -->
            <div class="mb-3">
                <label for="content" class="form-label">내용</label>
                <textarea 
                    id="content"
                    name="content"
                    class="form-control"
                >{{ $board->content }}</textarea>
            </div>

            <!-- 수정하기 버튼 -->
            <div>
                <button type="submit" class="btn btn-primary">수정하기</button>
            </div>
        </form>
    </div>
@stop
