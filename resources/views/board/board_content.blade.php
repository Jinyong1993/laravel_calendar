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
<form method="get" action="{{route('board.content')}}">
<input type="hidden" name="board_id" value="{{isset($select->board_id) ? $select->board_id : null}}"/>
<div class="d-flex justify-content-center text-truncate">
    <h1>{{$select->title}}</h1>
</div>
<hr/>
<div class="d-flex board_head">
    <div class="p-2 m-1" style="text-align:left">番号：{{$select->board_id}}</div>
    <div class="p-2 flex-fill"></div>
    <div class="p-2 m-1" style="text-align:right">作成日：{{$select->created_at}}</div>
</div>
<hr/>
<div class="container board_body">
    <div class="row">
        <div class="col-auto p-3 overflow-auto"style="width:100%; word-break:break-all;">
            {{$select->note}}
        </div>
    </div>
</div>

@php
$img_ext = array('jpg','jpeg','png','gif');
@endphp

<div class="file">
    @if($board_file_select ?? null)
    <hr>
    @foreach($board_file_select as $file)
    <div class="p-2" style="">添付ファイル</div>
    <div class="row">
        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
            style="text-align: center">ファイル名：
            <a href="{{route('board.download', ['file_id' => $file->file_id])}}">
                {{$file->name}}&nbsp;
                <i class="bi bi-download"></i>
            </a>
            @if(in_array($file->extension, $img_ext))
                <button type="button" 
                        class="file_preview btn btn-primary btn-sm" 
                        value="{{asset('storage/'.$file->file_id.'.'.$file->extension)}}"
                        data-bs-toggle="modal" 
                        data-bs-target="#preview_modal">プレビュー
                </button>
            @endif
        </div>
        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
            style="text-align: center">サイズ：{{ceil($file->size / 1024).' mb'}}</div>
        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
            style="text-align: center">拡張子：{{$file->extension}}</div>
        <div class="p-2 col-sm-12 col-md-3 col-lg-3 col-xl-3" 
            style="text-align: center">アプロード時刻：{{$file->created_at}}</div>
        <hr>
    </div>
    @endforeach
    @endif
</div>
</form>
<hr/>
@php
$user_id = auth()->user()->id;
@endphp
@foreach($comment_select as $comment)
<div class="comment_contents container">
    <input type="hidden" class="coment_id_hidden" value="{{$comment->comment_id}}"/>
    <div class="row m-1">
        <div class="col-sm-12 
                    col-md-6 
                    col-lg-6 
                    col-xl-6 
                    p-2">
                    作成者：{{$comment->user_id}}
        </div>
        <div class="col-sm-12 
                    col-md-6 
                    col-lg-6 
                    col-xl-6 
                    p-2">
                    作成日：{{$comment->created_at}}
        </div>
        <div class="comment_space col-12 p-2" style="word-break:break-all;">
            <span>{{$comment->note}}</span>
        </div>
        @if($user_id == $comment->user_id)
        <div class="input_space col-12 p-2" style="text-align:right;">
            <input type="button" class="comment_update_button btn btn-primary" value="修正"/>
            <button type="button" class="comment_delete_button btn btn-danger"  data-bs-toggle="modal" data-bs-target="#comment_delete_modal">
                削除
            </button>
        </div>
        @endif
    </div>
</div>
@endforeach
<hr/>
<form method="POST" action="{{route('board.comment_create')}}">
<input type="hidden" name="board_id" value="{{isset($select->board_id) ? $select->board_id : null}}"/>
@csrf
<div class="d-flex border border-secondary">
    <div class="p-2">
        <input type="text" name="comment_note">
    </div>
    <div class="p-2">
        <input type="submit" value="コメント" class="btn btn-success"/>
    </div>
</div>
</form>

@if($select->user_id == auth()->user()->id)
    <a type="button" href="{{route('board.create_view', ['board_id' => $select->board_id])}}" class="btn btn-primary">修正</a>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_modal">
        削除
    </button>
@endif
<a type="button" href="{{route('board.index', ['sort' => 'board_id', 'order' => 'desc'])}}" class="btn btn-secondary">戻る</a>

{{-- preview modal --}}
<div class="preview modal fade" id="preview_modal" tabindex="0">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">プレビュー</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET" action="{{route('board.content')}}">
                    {{-- @foreach($board_file_select as $file) --}}
                        <img id="preview_img" 
                            alt="イメージ" 
                            width="500" 
                            height="500" 
                            {{-- value="{{$file->file_id}}" --}}
                            style="max-width:100%; height:auto;">
                    {{-- @endforeach --}}
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="delete_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            本当に削除しますか？
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取り消し</button>
            <form method="POST" action="{{route('board.delete')}}">
            @csrf
                <input type="hidden" name="board_id" value="{{isset($select->board_id) ? $select->board_id : null}}"/>
                <input type="submit" class="btn btn-danger" value="削除"/>
            </form>
        </div>
        </div>
    </div>
</div>

<!--comment delete modal -->
<div class="modal fade" id="comment_delete_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">削除</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            本当に削除しますか？
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取り消し</button>
            <form method="POST" action="{{route('board.comment_delete')}}">
            @csrf
                <input type="hidden" name="comment_id" value="{{isset($comment->comment_id) ? $comment->comment_id : null}}"/>
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
        $('.file_preview').click(function(){
            var src = $(this).val()
            $('.preview').find('img').attr('src', src)
        })

        $('.comment_update_button').click(function(){
            var input_update = "<input type='submit' value='作成' class='input_saksei_button btn btn-success'/>"
            var input_cancel = "<input type='button' value='取り消し' class='input_cancel_button btn btn-secondary'/>"
            var input_text = "<textarea class='comment_saksei_note' style='resize:none;'></textarea>"
            var comment_note = $(this).closest('.comment_contents').find('span').text()
            $(this).closest('.comment_contents').find('.comment_space').append(input_text)
            $(this).closest('.comment_contents').find('.comment_saksei_note').val(comment_note)
            $(this).closest('.comment_contents').find('span').hide()
            $(this).closest('.comment_contents').find('.input_space').append(input_update)
            $(this).closest('.comment_contents').find('.input_space').append(input_cancel)
            $(this).closest('.comment_contents').find('.comment_delete_button').hide()
            $(this).hide()

            $('.input_cancel_button').off('click')
            $('.input_cancel_button').click(function(){
                $(this).closest('.comment_contents').find('.comment_delete_button').show()
                $(this).closest('.comment_contents').find('.comment_update_button').show()
                $(this).closest('.comment_contents').find('span').show()
                $(this).closest('.comment_contents').find('.input_saksei_button').hide()
                $(this).closest('.comment_contents').find('.comment_saksei_note').hide()
                $(this).hide()
            })

            $('.input_saksei_button').off('click')
            $('.input_saksei_button').click(function(){
                var comment_id = $(this).closest('.comment_contents').find('.coment_id_hidden').val()
                var comment_note = $(this).closest('.comment_contents').find('.comment_saksei_note').val()
                
                var comment_object = {
                    comment_id:comment_id,
                    comment_note:comment_note,
                }
    
                $.ajax({
                    url: "comment_update_ajax",
                    type: "post",
                    data: comment_object,
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                }).done(function(data) {
                    if(data.success){
                        location.reload()
                    } else {
                        alert(data.error)
                    }
                });

            })
        })
    })
</script>
@endsection
