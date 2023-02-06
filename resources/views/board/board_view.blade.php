@extends('layout.master')

@section('nav')
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{route('board.index')}}">掲示板</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{route('calendar.index')}}">カレンダー</a>
            </li>
            <li class="nav-item">
            <a class="nav-link disabled">Disabled</a>
            </li>
        </ul>
    </div>
        <div class="col-auto">
            <div class="nav navbar-nav navbar-left">
                <h3>{{auth()->user()->name}} 様</h3>
            </div>
        </div>
        <div class="col-auto">
        <a class="btn btn-info btn-sm" href="{{route('auth.user_info')}}">会員情報</a>
        </div>
    </form>
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <div class="col-auto">
            <button calss="btn btn-danger btn-sm" type="submit">ログアウト</button>
        </div>
    </form>
  </div>
</nav>
@endsection

@section('content')
<form method="get" action="{{route('board.index')}}" id="board_form">
<table class="table table-hover">
    <thead>
        <tr>
            <th><a type="button" class="sort_btn btn btn-light" onclick="sort('board_id', {{$sort == 'board_id' && $order != 'desc' ? 'true' : 'false'}})">番号</a></th>
            <th><a type="button" class="sort_btn btn btn-light" onclick="sort('title', {{$sort == 'title' && $order != 'desc' ? 'true' : 'false'}})">タイトル</a></th>
            <th><a type="button" class="sort_btn btn btn-light" onclick="sort('user_id', {{$sort == 'user_id' && $order != 'desc' ? 'true' : 'false'}})">作成者</a></th>
            <th><a type="button" class="sort_btn btn btn-light" onclick="sort('created_at', {{$sort == 'created_at' && $order != 'desc' ? 'true' : 'false'}})">作成日</a></th>
            <th><a type="button" class="sort_btn btn btn-light" onclick="sort('hit', {{$sort == 'hit' && $order != 'desc' ? 'true' : 'false'}})">アクセス数</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($board as $row)
        <tr>
            <th>{{$row->board_id}}</th>
            <td style="max-width:100px" class="text-truncate">
                <a href="{{route('board.content', ['board_id' => $row->board_id])}}">
                    {{$row->title}}
                </a>
            </td>
            <td>{{$row->user_id}}</td>
            <td>{{$row->created_at}}</td>
            <td>{{$row->hit}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        
    </tfoot>
</table>
<hr/>
<div class="p-2" style="text-align: right">
    <a type="button" href="{{route('board.create_view')}}" class="btn btn-success">作成</a>
</div>
</form>
<div class="page d-flex justify-content-center">{{ $board->appends(request()->query())->links() }}</div>
@endsection

@section('script')
<script>
    function sort(col_name, order, page)
    {
        var col_input = "<input type='hidden' name='sort' value='"+col_name+"'/>"
        if(order){
            var order_type = "<input type='hidden' name='order' value='desc'/>"
        } else {
            var order_type = "<input type='hidden' name='order' value='asc'/>"
        }
        $('#board_form').append(col_input)
        $('#board_form').append(order_type)
        $('#board_form').submit()
    }

    
</script>
@endsection