<form method="post" action="{{ route('login')}}">
    @csrf
    メール <input type="text" name="email"/><br>
    パスワード <input type="password" name="password"/><br>
    <button type="submit">登録</button>
    <a class="btn btn-default" href="{{ route('register')}}">会員登録</a>
</form>

<hr>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif