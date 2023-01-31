@extends('layout.master')

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

 </head>
 <body>
     <form method="POST" action="{{route('auth.user_update')}}">
        @csrf
     <table class="table">
         <thead>
            <tr>
                <th class="glyphicon glyphicon-user">会員情報</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>名前</td>
                <td><input class="form-control" name="name" type="text" placeholder="名前を入力してください" value="{{$select->name}}"></td>
            </tr>
            <tr>
                <td>メール</td>
                <td style="color:grey">{{$select->email}}</td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input class="form-control" name="password" type="password" placeholder="現在パスワードを入力してください" autocomplete="off"></td>
            </tr>
            <tr>
                <td>メール変更</td>
                <td><input class="form-control" name="email_change" type="email" placeholder="新しいメールを入力してください" autocomplete="off"></td>
            </tr>
            <tr>
                <td>メール変更確認</td>
                <td><input class="form-control" name="email_change_confirmation" type="email" placeholder="新しいメールをもう一度入力してください" autocomplete="off"></td>
            </tr>
            <tr>
                <td>パスワード変更</td>
                <td><input class="form-control" name="password_change" type="password" placeholder="新しいパスワードを入力してください" autocomplete="off"></td>
            </tr>
            <tr>
                <td>パスワード変更確認</td>
                <td><input class="form-control" name="password_change_confirmation" type="password" placeholder="新しいパスワードをもう一度入力してください" autocomplete="off"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">
                    <input class="btn btn-success btn-submit" type="submit" value="修正"/>
                    <a class="btn btn-default" href="{{route('calendar.index')}}">カレンダーへ</a>
                </td>
            </tr>
        </tfoot>
    </table>
    </form>
</body>
</html>

@endsection