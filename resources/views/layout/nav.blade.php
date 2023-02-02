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