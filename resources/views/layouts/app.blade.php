<!doctype html>
<html lang="kr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CRUD')</title>
</head>
<body>
@include('layouts.navbar')

<h3>안녕하세요 {{$user->name}} 님</h3>

@section('section')
@show
</body>
</html>