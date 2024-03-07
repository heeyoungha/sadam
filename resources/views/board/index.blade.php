@extends('layouts.app')

@section('section')
<style>
        /* 게시판 스타일링 */
        section {
            margin: 20px 0;
        }

        form {
            margin-bottom: 20px;
        }

        select, input {
            padding: 8px;
            margin-right: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        div > a {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
    <section>
        <div>
            게시판
        </div>
    </section>
    <form action="{{ route('board_index') }}" method="GET" id="search_form">
        <div>
            <select name="filter">
                <option value="titleContent" {{ $filter === 'titleContents' ? 'selected' : null }}>제목 + 내용</option>
                <option value="content" {{ $filter === 'contents' ? 'selected' : null }}>내용</option>
                <option value="actor" {{ $filter === 'actor' ? 'selected' : null }}>작성자</option>
            </select>
            <div>
                <input 
                    type="text"
                    id=""
                    name="searchTitle"
                    placeholder="검색어를 입력하세요"
                    value="{{ $searchTitle }}"    
                    />
                <button type="submit">
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
            <col style="width: 50%"/>
            <col style="width: 11%"/>
            <col style="width: 11%"/>
            <col style="width: 8.333%"/>
            <col style="width: 10%"/>
        </colgroup>
        <thead>
            <th>제목</th>
            <th>작성자</th>
            <th>작성일자</th>
            <th>조회</th>
            <th>공감</th>
        </thead>
        <tbody>
            @if ($countCollection > 0)
                @foreach ($newCollection as $index => $item)
                    <tr>
                        <td>
                            <a href="/board/{{ $item['id'] }}">
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
@stop