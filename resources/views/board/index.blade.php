@extends('layouts.app')

@section('section')
    <section class="w-2/3 mx-auto mt-8 text-green-500">
        <div class="text-2xl">
            게시판
        </div>
    </section>
    <form action="{{ route('board_index') }}" method="GET" id="search_form">
        <div>
            <select>
                <option value="titleContent" @if ($selectValue === "titleContent") selected @endif>제목 + 내용</option>
                <option value="titleContent" @if ($selectValue === "titleContent") selected @endif>내용</option>
                <option value="titleContent" @if ($selectValue === "titleContent") selected @endif>작성자</option>
            </select>
            <div>
                <input 
                    type="text"
                    id="searchTitle"
                    name=""
                    placeholder="검색어를 입력하세요"
                    value="{{ $searchTitle }}"    
                    />
                <button>
                    <span>검색</span>
                </button>
            </div>
        </div>
    </form>
    <!-- 게시물 -->
    <div>
        <h3><span>{{ $countCollection }}</span>개</h3>
        <a href="/board/create">
            글쓰기
        </a>
    </div>
    <table>
        <caption></caption>
        <colgroup>
            <col style="width: 8.333%"/>
            <col style="width: 50%"/>
            <col style="width: 11%"/>
            <col style="width: 11%"/>
            <col style="width: 8.333%"/>
            <col style="width: 10%"/>
        </colgroup>
        <thead>
            <th>번호</th>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일자</th>
            <th>조회</th>
            <th>공감</th>
        </thead>
        <tbody>
            @if ($countCollection > 0)
                @foreach ($currentPageItems as $index => $item)
                    <tr>
                        <td></td>
                        <td>
                            <a href="/board/show/{{ $item['id'] }}">
                                <p>{{ $item['title'] }}</p>
                                <span></span>
                            </a>
                        </td>
                        <td>{{ $item['uname'] }}</td>
                        <td>{{ $item['created_at'] }}</td>
                        <td>{{ $item['view_cnt'] }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @else
                <!-- 게시물이 없을 경우 -->
                <tr>
                    <td다>
                        등록된 게시물이 없습니다
                    </td다
                </tr>
            @endif
        </tbody>
    </table>
    <div>
        {!! $paginator->links('vendor.pagination.custom')->render() !!}
    </div>
@stop