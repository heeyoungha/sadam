<!-- 부트스트랩 CSS 파일 추가 -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">SADAM</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="{{route('board.index')}}">게시판</a>
            </li>
            @can('view', App\Models\Bookmark::class)
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('bookmark.index') }}">북마크 관리</a>
            </li>
            @endcan
            <li class="nav-item active">
                <a class="nav-link" href="#">회원관리</a>
            </li>
            <!-- Add more menu items as needed -->
        </ul>
    </div>
</nav>
