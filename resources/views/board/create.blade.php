@extends('layouts.app')

@section('section')
    <div class="container">
        <form action="{{ route('board_store') }}" method="post">
            @csrf

            <div class="mb-4">
                <h2>게시판</h2>
                <h3>글쓰기</h3>
            </div>

            <!-- 제목 -->
            <div class="mb-3">
                <label for="title" class="form-label">제목</label>
                <input 
                    type="text"
                    id="title"
                    name="title"
                    placeholder="제목을 입력하세요"
                    class="form-control"
                />
            </div>

            <!-- 에디터 -->
            <div class="mb-3">
                <label for="editordata" class="form-label">내용</label>
                <textarea 
                    id="editordata"
                    name="editordata"
                    placeholder="내용을 입력하세요"
                    class="form-control"
                ></textarea>
            </div>

            <!-- 버튼 -->
            <div>
                <button type="submit" class="btn btn-primary">등록하기</button>
            </div>
        </form>
    </div>
@stop
