@extends('layout.master')

@section('content')
@php
$first = "$year/$month/1";
$time_stamp = strtotime($first);
$total_day = date("t", $time_stamp);

$start_day_week = (int) date("w", $time_stamp);
$total_week = (int) ceil(($total_day + $start_day_week) / 7);
@endphp
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ route('calendar.index', ['year' => $year, 'month' => $month]) }}">カレンダー</a>
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
    <form class="navbar-form navbar-left row" method="GET" action="{{ route('calendar.index', ['year' => $year, 'month' => $month]) }}">
        <div class="col-auto dropdown">
            <button type="button" 
                    class="btn btn-secondary btn-sm dropdown-toggle" 
                    id="my_color_show" 
                    data-bs-toggle="dropdown">マイカラー</button>
            <ul class="dropdown-menu">
                @php foreach($tag_query as $row) : @endphp
                <li class="dropdown-item">
                    <label>
                        <input type="checkbox"
                                class="my_color_chk"
                                value="{{$row->tag_id}}"
                                checked>&nbsp;{{$row->tag_name}}
                    </label>
                </li>
                @php endforeach @endphp
            </ul>
        </div>
        <div class="col-auto">
            <?php if($month == 1) : ?>
                <a class="btn btn-secondary btn-sm" href="{{ route('calendar.index', ['year' => ($year-1), 'month' => '12']) }}" role="button">先月</a>
            <?php else : ?>
                <a class="btn btn-secondary btn-sm" href="{{ route('calendar.index', ['year' => $year, 'month' => ($month-1)]) }}">先月</a>
            <?php endif ?>
            </div>
            <div class="col-auto">
            <?php if($month == 12) : ?>
                <a class="btn btn-secondary btn-sm" href="{{ route('calendar.index', ['year' => ($year+1), 'month' => '1']) }}">来月</a>
            <?php else : ?>
                <a class="btn btn-secondary btn-sm" href="{{ route('calendar.index', ['year' => $year, 'month' => ($month+1)]) }}">来月</a>
            <?php endif ?>
            </div>
        <div class="col-auto">
            <input type="text" 
                    id="year_search" 
                    name="year" 
                    value="{{$year}}" 
                    class="date form-control form-control-sm" 
                    placeholder="年" 
                    autocomplete="off">
        </div>
        <div class="col-auto">
            <input type="text" 
                    id="month_search" 
                    name="month" 
                    value="{{$month}}" 
                    class="date form-control form-control-sm" 
                    placeholder="月" 
                    autocomplete="off">
        </div>
        <div class="col-auto">
            <button type="submit" id="search" class="btn btn-secondary btn-sm">検索</button>
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
    <div class="col-auto">
        <form method="post" action="{{ route('logout') }}">
        @csrf
            <button calss="btn btn-danger btn-sm" type="submit">ログアウト</button>
        </form>
    </div>
  </div>
</nav>

    <form method="POST" action="#">
        <input type="text" id="year_ajax" name="year" value="{{$year}}"><input type="text" id="month_ajax" name="month" value="{{$month}}">
    
    <table class="table table-bordered" id="calendar">
        <?php
        $first = "$year/$month/1";
        $time_stamp = strtotime($first);
        $total_day = date("t", $time_stamp);

        $start_day_week = (int) date("w", $time_stamp);
        $total_week = (int) ceil(($total_day + $start_day_week) / 7);
        ?>
        <thead>
            <tr>
                <th style="color:red">日&nbsp;<input class="chk_date" data-weekday="Sunday" type="checkbox"/></th>
                <th>月&nbsp;<input class="chk_date" data-weekday="Monday" type="checkbox"/></th>
                <th>火&nbsp;<input class="chk_date" data-weekday="Tuesday" type="checkbox"/></th>
                <th>水&nbsp;<input class="chk_date" data-weekday="Wednesday" type="checkbox"/></th>
                <th>木&nbsp;<input class="chk_date" data-weekday="Thursday" type="checkbox"/></th>
                <th>金&nbsp;<input class="chk_date" data-weekday="Friday" type="checkbox"/></th>
                <th style="color:blue">土&nbsp;<input class="chk_date" data-weekday="Saturday" type="checkbox"/></th>
            </tr>
        </thead>
    
        <tbody>
        @php
        $day = 1;
        $today = date("Y-n");
        $today1 = date("d");
        $this_month = strtotime($today) == strtotime("$year-$month");
        @endphp
    
    <?php
    for($i=0; $i<$total_week; $i++){
    ?>
        <tr>
        <?php
        for($j=0; $j<7; $j++){
            $day_week = date("l", strtotime("$year/$month/$day"));
            $query = isset($select_query[$day]) ? $select_query[$day] : null;
        ?>
            <?php if (($day > 1 || $j >= $start_day_week) && ($total_day >= $day)) : ?>
                <td>
                    <div class="d-flex align-items-center">
                        <?php if($day_week == "Sunday") : ?>
                            <span style="color:red">{{$day}}</span>
                        <?php elseif($day_week == "Saturday") : ?>
                            <span style="color:blue">{{$day}}</span>
                        <?php else : ?>
                            <span style="color:black">{{$day}}</span>
                        <?php endif ?>
                        <?php if(($today1 == $day) && ($this_month)) : ?>
                            <span style="color:red" id="today_highlight">本日</span>
                        <?php endif ?>  
                        <div class="ps-2">
                            <input type="checkbox" 
                                   data-weekday="{{$day_week}}" 
                                   class="chk pl-2" 
                                   name="check_test" 
                                   value="{{$day}}"/>
                        </div>
                    <div style="flex-grow:1"></div>
                    <button style="flex-grow:0" 
                            class="plus_button btn btn-primary btn-sm" 
                            type="button" 
                            data-bs-toggle="modal" 
                            data-bs-target=".plus">+
                    </button><br>
                </div>
                <?php if($query) : ?>
                <?php foreach($query as $row) : ?>
                    <div>
                        <input type="button" 
                        class="title"
                        data-color="{{$row->tag_id}}"
                        data-id="{{$row->event_id}}" 
                        value="{{$row->title}}"
                        data-bs-toggle="modal" 
                        data-bs-target=".plus" 
                        style="background-color:{{$row->tag_color}}"/>
                    </div>
                <?php endforeach ?>
                <?php endif ?>
                <?php $day++ ?>
                </td>
            <?php else : ?>
                <td></td>
            <?php endif ?>
        <?php
        }
    }
    ?>
    </tbody>

    <tfoot>
        
    </tfoot>
    </table>
    <input type="checkbox" id="check_all"/>

	<button type="button" 
            class="btn btn-primary" 
            data-bs-toggle="modal" 
            data-bs-target="#calendar_search">検索
    </button>
    <button type="button" 
            id="color_my" 
            class="btn btn-warning" 
            data-bs-toggle="modal" 
            data-bs-target="#color_edit">マイカラー設定
    </button>
    </form>

    <!-- + button modal -->
<div class="modal plus" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">予定追加</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>

			<div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>期間</th>
                            <td>
                                <input type="text" 
                                        id="date_from" 
                                        name="date_from" 
                                        value="" 
                                        class="date form-control form-control-sm" 
                                        placeholder="期間" 
                                        autocomplete="on"> ~ 
                                <input type="text" 
                                        id="date_to" 
                                        name="date_to" 
                                        value="" 
                                        class="date form-control form-control-sm" 
                                        placeholder="期間"
                                        autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <th>予定</th>
                            <td>
                                <input type="text" id="plus_title" value="" placeholder="予定を入力する。"/>
                            </td>
                        </tr>
                        <tr>
                            <th>備考</th>
                            <td>
                                <textarea id="plus_textarea" placeholder="備考"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>マイカラー</th>
                            <td>
                                <select class="form-select" id="my_color_list">
                                    <option value="" selected>マイカラー</option>
                                    <?php foreach($tag_query as $row) : ?>
                                        <option value="{{$row->tag_id}}">
                                            {{$row->tag_name}}
                                        </option>
                                    <?php endforeach ?>
                                    </option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
			</div>

			<div class="modal-footer">
				<button id="plus_delete" type="button" class="btn btn-danger">削除</button>
				<button id="plus_cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
				<button id="plus_save" type="button" class="btn btn-success">保存</button>
			</div>
		</div>
	</div>
</div>


<!-- search modal -->
<div class="modal fade" id="calendar_search" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
      	<div class="modal-header">
        	<h1 class="modal-title fs-5" id="exampleModalLabel">検索</h1>
        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      	</div>

      	<div class="modal-body">
            <div class="text_input_group">
                <label for="text_search" class="control-label">検索したい予定を入力して下さい。</label>
                <input type="text" class="form-control" id="text_search">
                <select class="form-select" id="my_color_search_list">
                    <option value="" selected>マイカラー</option>
                    <?php foreach($tag_query as $row) : ?>
                        <option value="{{$row->tag_id}}">
                            {{$row->tag_name}}
                        </option>
                    <?php endforeach ?>
                </select>
                <button class="btn btn-secondary" id="text_search_input"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> 入力</button>
            </div>
            <div class="output_group">
                <table class="table table">
                <thead>
                    <tr>
                        <th>日付</th>
                        <th>マイカラー</th>
                        <th>内容</th>
                        <th>リンク</th>
                    </tr>
                </thead> 
                <tbody id="search_result">

                </tbody>   
                </table>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
        </div>
    </div>
  </div>
</div>

<!-- color edit modal -->
<div class="modal fade" id="color_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
      	<div class="modal-header">
        	<h1 class="modal-title fs-5" id="exampleModalLabel">マイカラー設定</h1>
        	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      	</div>

      	<div class="modal-body">
            <table class="table">
                <thead>
                    
                </thead>
                <tbody>
                    <tr>
                        <th>名前</th>
                        <td><input type="text" id="color_edit_name"/></td>
                    </tr>
                    <tr>
                        <th>説明</th>
                        <td><textarea id="color_edit_note"></textarea></td>
                    </tr>
                    <tr>
                        <th>カラー</th>
                        <td id="other_position">
                            <select class="form-select" id="color_edit_list">
                                <option value="" selected>カラーを選択する</option>
                                <option value="#FF0000">レッド</option>
                                <option value="#0000FF">ブルー</option>
                                <option value="#008000">グリーン</option>
                                <option value="#FFFF00">イエロー</option>
                                <option value="#FF4500">オレンジ</option>
                                <option value="#8B00FF">パープル</option>
                                <option value="#FFC0CB">ピンク</option>
                                <option value="other">その他</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>マイカラー</th>
                        <td>
                            <select class="form-select" id="my_color_edit_list">
                                <option value="" selected>新規作成</option>
                                <?php foreach($tag_query as $row) : ?>
                                    <option value="{{$row->tag_id}}">
                                        {{$row->tag_name}}
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button id="color_edit_del" type="button" class="btn btn-danger">削除</button>
            <button id="color_edit_cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
            <button id="tag_edit_save" type="button" class="btn btn-success">保存</button>
        </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
    function validate(year, month) {
        if(isNaN(year) || isNaN(month) || (year > 9999 || month > 12) || (year < 1 || month < 1)){
            return true;
        } else {
            return false;
        }
    }

    $(function(){
        var days = [];

        $("#search").click(function (event){
            var result = validate($("#year_search").val(), $("#month_search").val());
            if(result){
                $("#alert_message").show();
                event.preventDefault();
            }
        });

        $("#output").click(function (){
            $(':checkbox[name="check_test"]:checked').each(function () {
                days.push($(this).val());
                $("#output_area").text(days);
            });
        });

        $("#del").click(function (){
            var days = []
            var ids = []
            $(':checkbox[name="check_test"]:checked').each(function () {
                days.push($(this).val());
            });
            $(':checkbox[name="check_test"]:checked').closest('td').find('.title').each(function(){
                ids.push($(this).data('id'));
            });
            console.log(days);
            console.log(ids);

            var plus_object = {
                id:ids,
                year:$("#year_ajax").val(),
                month:$("#month_ajax").val(),
                day:days,
                title:$("#plus_title").val(),
                text:$("#plus_textarea").val(),
                color_id:$("#plus_color").val(),
            }
            console.log(plus_object)

            $.ajax({
                url: "delete_ajax",
                type: "post",
                data: plus_object,
                dataType: "json"
            }).done(function(data) {
                location.reload()
                alert("処理しました。")
            })
        });

        $("#input").click(function (){
            var input_text = $("#input_text").val();
            $(':checkbox[name="check_test"]:checked').parent("td").children("textarea").val(input_text);
        });

        $('.color_change').change(function (){
            $(this).parent("td").css({
                "background-color":$(this).val()
            })
        });

        $("#date_from").datepicker( {
            language: "ja",
            format: "yyyy/mm/dd"
        });
        $("#date_to").datepicker( {
            language: "ja",
            format: "yyyy/mm/dd"
        });

        $("#year_search").datepicker( {
            language: "ja",
            minViewMode: 2,
            format: "yyyy"
        });

        $("#month_search").datepicker( {
            language: "ja",
            minViewMode: 1,
            format: "m"
        });

        $(".chk_date").click(function(){
            $("input[data-weekday="+$(this).data("weekday")+"]").prop("checked", $(this).is(":checked"));
        });

        $("#check_all").click(function (){
            if($(this).is(":checked")) {
                $("input[name=check_test]").prop("checked", true);
            } else {
                $("input[name=check_test]").prop("checked", false);
            } 
        });

        $(".my_color_chk").click(function(){
            $("input.title[data-color="+$(this).val()+"]").fadeToggle($(this).is(":checked"))
        });

        $("#color_change_all").change(function (){
            $("input[name=check_test]:checked").parent("td").css({
                "background-color":$(this).val()
            })
            
            $("input[name=check_test]:checked").parent("td")
            .children(".color_change").val($(this).val());
        })

        // $("#today_highlight").parent("td").css("background", "yellow");
        $("#today_highlight").parent("td").animate({
            borderWidth:3
        },5000);

        $("#color_save").click(function(){
            var color_object = {
                color_name:$("#color_name").val(),
                color_note:$("#color_note").val(),
                color_color:$("#color_list").val(),
            }

            $.ajax({
                url: "color_save_ajax",
                type: "post",
                data: color_object,
                dataType: "json"
            }).done(function(data) {
                if(data.success){
                    location.reload()
                } else {
                    alert(data.error)
                }
            })
        });

        $("#color_edit_list").on("change", function(){
            if($(this).val() == "other"){
                $("#color_edit_list").remove();
                var input_color = '<input type="color" class="form-control form-control-color" id="color_edit_list" value="#ffffff">'
                $("#other_position").append(input_color)
            }
        });

        // $("#color_my").click(function(){
        //     $("#my_color_edit_list").empty()
        //     $.ajax({
        //         url: "my_color_ajax",
        //         type: "get",
        //         dataType: "json"
        //     }).done(function(data) {
        //         var default_option = "<option selected>マイカラー</option>"
        //         $("#my_color_edit_list").append(default_option)
        //         $.each(data, function(i,v){
        //             var my_option = "<option value="+v.color_color+">"+v.color_name+"</option>"
        //             $("#my_color_edit_list").append(my_option)
        //         });
        //     });
        // });

        $("#my_color_edit_list").on("change", function(){
            var tag_id_object = {
                tag_id:$("#my_color_edit_list").val(),
            }

            $.ajax({
                url: "color_select_ajax",
                type: "get",
                data: tag_id_object,
                dataType: "json"
            }).done(function(data){
                $("#color_edit_name").val(data.tag_name)
                $("#color_edit_note").val(data.tag_note)
            });
        });

        $("#tag_edit_save").click(function(){
            var tag_edit_object = {
                tag_id:$("#my_color_edit_list").val(),
                tag_name:$("#color_edit_name").val(),
                tag_note:$("#color_edit_note").val(),
                tag_color:$("#color_edit_list").val(),
            }

            $.ajax({
                url: "color_update",
                type: "post",
                data: tag_edit_object,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).done(function(data) {
                console.log(data)
                if(data.success){
                    location.reload()
                } else {
                    alert(data.error)
                }
            })
        });

        $("#color_edit_del").click(function(){
            var tag_del_object = {
                tag_id:$("#my_color_edit_list").val(),
            }

            $.ajax({
                url: "color_delete_ajax",
                type: "post",
                data: tag_del_object,
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
        });

        $(".plus_button").click(function (){
            $("#plus_delete").hide();
			var plus_position = $(this).closest('td').find(':checkbox[name=check_test]').val()
			var year = $('#year_ajax').val()
			var month = $('#month_ajax').val()

            if(month < 10){
                month = '0' + month
            }
            if(plus_position < 10){
                plus_position = '0' + plus_position
            }

            $("#date_from").datepicker('update', year+"/"+month+"/"+plus_position)
            $("#date_to").datepicker('update', year+"/"+month+"/"+plus_position)
            $("#plus_title").val(null)
            $("#plus_textarea").val(null)
            
            $("#plus_save").off("click")
            $("#plus_save").click(function (){
                var plus_object = {
                    date_from:$("#date_from").val(),
                    date_to:$("#date_to").val(),
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                    tag_id:$("#my_color_list").val(),
                }
                
                $.ajax({
                    url: "update",
                    type: "post",
                    data: plus_object,
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
            });
        });

        $(".title").click(function(){
			var day = $(this).closest('td').find(':checkbox[name=check_test]').val()
            var event_id = $(this).data("id")
            console.log(event_id)

            var id_object = {
                event_id:$(this).data("id")
            }
            
            $.ajax({
                url: "select_ajax",
                type: "get",
                data: id_object,
                dataType: "json"
            }).done(function(data) {
                $("#date_from").val(data.date_from);
                $("#date_to").val(data.date_to);
                $("#plus_title").val(data.title);
                $("#plus_textarea").val(data.text);
                $("#my_color_list").val(data.color_id);
            })
            
            $("#plus_save").off('click')
            $("#plus_delete").off('click')
            $("#plus_save").click(function (){
                var plus_object = {
                    event_id:event_id,
                    date_from:$("#date_from").val(),
                    date_to:$("#date_to").val(),
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                    tag_id:$("#my_color_list").val(),
                }
                $.ajax({
                    url: "update",
                    type: "post",
                    data: plus_object,
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
            });

            $("#plus_delete").click(function(){
                var plus_object = {
                    event_id:event_id,
                    date_from:$("#date_from").val(),
                    date_to:$("#date_to").val(),
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                    tag_id:$("#my_color_list").val(),
                }
                $.ajax({
                    url: "delete_ajax",
                    type: "post",
                    data: plus_object,
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
            });
        });

        $("#text_search_input").click(function() {
            $("#text_search").val();

            $('#search_result').empty();

            var search_object = {
                text:$("#text_search").val(),
                tag_id:$("#my_color_search_list").val(),
            }

            $.ajax({
                url: "search_ajax",
                type: "get",
                data: search_object,
                dataType: "json" // data = JSON.parse(data)
            }).done(function(data) {
                $.each(data, function(i,v){
                    var from = convert_japanese(v.date_from)
                    var to = convert_japanese(v.date_to)
                    var date = new Date(v.date_from)
                    var year = date.getFullYear()
                    var month = (date.getMonth()+1)
                    var a = "<a href=index?year="+year+"&month="+month+">link</a>"
                    var tr = '<tr><td>'+from+'~'+to+'</td><td>'+v.tag_name+'</td><td>'+v.text+'</td><td>'+a+'</td></tr>'
                    
                    $('#search_result').append(tr)
                })
            })
        });

        $("#chk_plus").click(function(){
            var days = []
            $(':checkbox[name="check_test"]:checked').each(function () {
                days.push($(this).val());
            });

            $("#plus_title").val(null)
            $("#plus_textarea").val(null)

            $("#plus_save").off('click')
            $("#plus_save").click(function (){
                var plus_object = {
                    year:$("#year_ajax").val(),
                    month:$("#month_ajax").val(),
                    day:days,
                    title:$("#plus_title").val(),
                    text:$("#plus_textarea").val(),
                    color_id:$("#my_color_list").val(),
                }
                $.ajax({
                    url: "plus_ajax_controller",
                    type: "post",
                    data: plus_object,
                    dataType: "json"
                }).done(function(data) {
                    if(data.success){
                        location.reload()
                    } else {
                        alert(data.error)
                    }
                })
            });
        });

    });

    function convert_japanese(date)
    {
        var convert_date = new Date(date)
        var convert_result = convert_date.getFullYear() + "年" + (convert_date.getMonth()+1) + "月" + convert_date.getDate() + "日"
        return convert_result
    }
</script>
@endsection