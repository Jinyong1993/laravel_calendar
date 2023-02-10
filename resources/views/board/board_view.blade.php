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
<div class="accordion" id="search_accordion">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#search">
                検索
            </button>
        </h2>
        <div id="search" 
            class="accordion-collapse collapse" 
            data-bs-parent="#search_accordion">
            <div class="accordion-body">
                <form action="{{route('board.index')}}" method="GET">
                    <select name="category" class="form-select" id="category">
                        <option value="" {{empty($category) ? 'selected' : ''}}>
                            未選択
                        </option>
                        <option value="{{$board_col[1]}}" {{$category == $board_col[1] ? 'selected' : ''}}>
                            タイトル
                        </option>
                        <option value="{{$board_col[2]}}" {{$category == $board_col[2] ? 'selected' : ''}}>
                            内容
                        </option>
                        <option value="{{$board_col[3]}}" {{$category == $board_col[3] ? 'selected' : ''}}>
                            作成者
                        </option>
                    </select>
                    <div class="col-auto p-2">
                        <input type="text" id="keyword_search" name="keyword_search" value="{{$keyword_search ?? null}}"/>
                    </div>
                    <div class="col-auto p-2">
                        <input type="text" 
                        id="date_from" 
                        name="date_from" 
                        value="{{$date_from ?? null}}" 
                        class="date form-control form-control-sm" 
                        placeholder="期間" 
                        autocomplete="off"> ~ 
                        <input type="text" 
                        id="date_to" 
                        name="date_to" 
                        value="{{$date_to ?? null}}" 
                        class="date form-control form-control-sm" 
                        placeholder="期間"
                        autocomplete="off">
                    </div>
                    <div class="col p-2">
                        <input type="submit" value="検索" class="btn btn-success btn-sm"/>
                        <input type="button" id="search_clear" value="検索条件クリア" class="btn btn-secondary btn-sm"/>
                    </div>
                    <input type="hidden" name="sort" value="{{$sort}}"/>
                    <input type="hidden" name="order" value="{{$order}}"/>
                </form>
            </div>
        </div>
    </div>
</div>
<form method="get" action="{{route('board.index')}}" id="board_form">
    <input type="hidden" name="date_from" value="{{$date_from}}"/>
    <input type="hidden" name="date_to" value="{{$date_to}}"/>
    <input type="hidden" name="category" value="{{$category}}"/>
    <input type="hidden" name="keyword_search" value="{{$keyword_search}}"/>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('board_id', 
                                    {{$sort == 'board_id' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">番号</a>
                    @if($order == 'desc' && $sort == 'board_id')
                        <i class="bi bi-sort-down"></i>
                    @else
                        <i class="bi bi-sort-up-alt"></i>
                    @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('title', 
                                    {{$sort == 'title' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">タイトル</a>
                        @if($order == 'desc' && $sort == 'title')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('comment_count', 
                                    {{$sort == 'comment_count' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">コメント数</a>
                        @if($order == 'desc' && $sort == 'comment_count')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('user_id', 
                                    {{$sort == 'user_id' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">作成者</a>
                        @if($order == 'desc' && $sort == 'user_id')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('created_at', 
                                    {{$sort == 'created_at' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">作成日</a>
                        @if($order == 'desc' && $sort == 'created_at')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('hit', 
                                    {{$sort == 'hit' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">アクセス数</a>
                        @if($order == 'desc' && $sort == 'hit')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('file_count', 
                                    {{$sort == 'file_count' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">ファイル数</a>
                        @if($order == 'desc' && $sort == 'file_count')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
                <th>
                    <a type="button" 
                        class="sort_btn btn btn-light" 
                        onclick="sort('file_size', 
                                    {{$sort == 'file_size' && $order != 'desc' ? 'true' : 'false'}}, 
                                    '{{isset($category) ? $category : null}}', 
                                    '{{isset($keyword_search) ? $keyword_search : null}}')">サイズ</a>
                        @if($order == 'desc' && $sort == 'file_size')
                            <i class="bi bi-sort-down"></i>
                        @else
                            <i class="bi bi-sort-up-alt"></i>
                        @endif
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($board as $row)
            @php
            $time_stamp = strtotime($row->created_at);
            $date_format = date('Y-m-d', $time_stamp);
            @endphp
            <tr>
                <th style="text-align: center">{{$row->board_id}}</th>
                <td style="max-width:100px; text-align:center;" class="text-truncate">
                    <a href="{{route('board.content', ['board_id' => $row->board_id])}}">
                        {{$row->title}}
                    </a>
                </td>
                <td style="text-align: center">
                    <a class="comment_preview" href="#">
                    {{$row->comment_count ?? 0}}
                    </a>
                </td>
                <td style="text-align: center">{{$row->user_id}}</td>
                <td style="text-align: center">{{$date_format}}</td>
                <td style="text-align: center">{{$row->hit}}</td>
                <td style="text-align: center">{{$row->file_count}}</td>
                <td style="text-align: center">{{ceil($row->file_size / 1024).' mb' ?? 0}}</td>
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

{{-- comment_preview modal --}}
<div class="modal fade" id="comment_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">コメントプレビュー</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            @foreach($board as $comment)
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
                                col-md-12 
                                col-lg-12 
                                col-xl-12
                                p-2">
                                作成日：{{$comment->created_at}}
                    </div>
                    <div class="comment_space col-12 p-2" style="word-break:break-all;">
                        <span>{{$comment->note}}</span>
                    </div>
                </div>
            </div>
            <hr>
            @endforeach
        </div>
        <div class="modal-footer">

        </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function sort(col_name, order)
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

    $(function(){
        $("#date_from").datepicker( {
            language: "ja",
            format: "yyyy-mm-dd"
        });

        $("#date_to").datepicker( {
            language: "ja",
            format: "yyyy/mm/dd"
        });

        $(".comment_preview").click(function(){

            var myModal = new bootstrap.Modal(document.getElementById('comment_modal'))
            myModal.show()
        })

        $("#search_clear").click(function(){
            $("#category").val(null)
            $("#keyword_search").val(null)
            $("#date_from").val(null)
            $("#date_to").val(null)
        })
    })

    
</script>
@endsection