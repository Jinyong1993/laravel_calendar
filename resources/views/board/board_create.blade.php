@extends('layout.master')

@section('nav')
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarToggler">
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
        <div class="col-2">
            <div class="nav navbar-nav navbar-left">
                <h3>{{auth()->user()->name}} 様</h3>
            </div>
        </div>
        <div class="col-3">
            <a class="btn btn-info btn-sm" href="{{route('auth.user_info')}}">会員情報</a>
        </div>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <div>
                <button calss="btn btn-danger btn-sm" type="submit">ログアウト</button>
            </div>
        </form>
    </div>
</nav>
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form method="POST" action="{{route('board.create')}}" enctype="multipart/form-data">
<input type="hidden" name="board_id" value="{{isset($select->board_id) ? $select->board_id : null}}"/>
@csrf
<h1>新規作成</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>タイトル</th>
            <td>
                <input name="title" 
                        type="text" 
                        placeholder="タイトルを入力してください。" 
                        value="{{isset($select->title) ? $select->title : null}}"/>
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
        <tr>
            <th>ファイル</th>
            <td>
                <input type="file" id="file" name="file[]" multiple/>
                <hr>
                @if(isset($select) && $select->board_file)
                    @foreach($select->board_file as $file)
                    <div class="row">
                        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
                            style="text-align: center">ファイル名：{{$file->name ?? null}}
                        </div>
                        <div class="p-2 col-sm-12 col-md-2 col-lg-2 col-xl-2" 
                            style="text-align: center">サイズ：{{ceil($file->size / 1024).' mb' ?? null}}</div>
                        <div class="p-2 col-sm-12 col-md-2 col-lg-2 col-xl-2" 
                            style="text-align: center">拡張子：{{$file->extension ?? null}}</div>
                        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
                            style="text-align: center">アプロード時刻：{{$file->created_at ?? null}}</div>
                        <div class="p-2 col-sm-12 col-md-1 col-lg-1 col-xl-1" 
                            style="text-align: center">
                            <button type="button" class="file_delete_button btn btn-danger btn-sm" 
                                    value="{{$file->file_id}}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <hr>
                    </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </tbody>
</table>
    <input type="submit" value="作成" class="btn btn-success"/>
    <a type="button" href="{{route('board.index', ['sort' => 'board_id', 'order' => 'desc'])}}" class="btn btn-light">取り消し</a>
</form>

<!-- delete modal -->
<div class="modal fade" id="delete_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">ファイル削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            本当に削除しますか？
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取り消し</button>
            <form method="POST" action="{{route('board.file_delete')}}">
            @csrf
                <input type="hidden" name="file_id" id="file_id" value=""/>
                <input type="submit" class="btn btn-danger" value="削除"/>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(function(){
        $('.file_delete_button').click(function(){
            var file_id = $(this).val()
            $('#file_id').val(file_id)
            var myModal = new bootstrap.Modal(document.getElementById('delete_modal'))
            myModal.show()
        })
    })
</script>
@endsection