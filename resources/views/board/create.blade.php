@extends('layouts.app')


@section('section')

    <form action="{{ route('board_store') }}" method="post">
        @csrf   
        <div>
            <div>
                <!-- 타이틀 -->
                <div>
                    <h2>게시판</h2>
                    <h3>글쓰기</h3>
                </div>
                <!-- 제목 -->
                <dl>
                    <dt>제목</dt>
                    <dd>
                        <div>
                            <input 
                                type="text"
                                id="title"
                                name="title"
                                placeholder="제목을 입력하세요"
                            />
                        </div>
                    </dd>
                </dl>
                <!-- 에디터 -->
                <div>
                    <input 
                            type="text"
                            id="title"
                            name="editordata"
                            placeholder="내용을 입력하세요"
                        />
                </div>
                <!-- 버튼 -->
                <div>
                    <button type="submit">등록하기</button>
                </div>
            </div>
        </div>
    </form>
@stop