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
<form method="POST" action="{{route('board.create')}}">
<input type="hidden" name="board_id" value="{{isset($select->board_id) ? $select->board_id : null}}"/>
@csrf
<h1>新規作成</h1>
<table class="table table-borderd">
    <thead>
        <tr>
            <th>タイトル</th>
            <td>
                <input name="title" type="text" placeholder="タイトルを入力してください。" value="{{isset($select->title) ? $select->title : null}}"/>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>内容</th>
            <td>
                <textarea name="note" placeholder="内容を入力してください。">{{isset($select->note) ? $select->note : null}}</textarea>
            </td>
        </tr>
    </tbody>
</table>
    <input type="submit" value="作成" class="btn btn-success"/>
    <a type="button" href="{{route('board.index')}}" class="btn btn-light">取り消し</a>
</form>
@endsection