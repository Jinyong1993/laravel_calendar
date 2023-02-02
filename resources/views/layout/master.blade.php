<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    @include('layout.styles')
    @include('layout.scripts')
    @yield('script')
</head>
<body>
    @yield('nav')
    @yield('content')
</body>
</html>