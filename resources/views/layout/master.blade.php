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
    @if ($errors->any())
        <div class="alert bg-danger bg-gradient">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @elseif (session('flash_message'))
        <div class="flash_message bg-success bg-gradient text-center py-3 my-0">
            {{ session('flash_message') }}
        </div>
    @endif
    @yield('content')
</body>
</html>