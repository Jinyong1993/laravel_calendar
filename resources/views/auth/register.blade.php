<form method="post" action="{{ route('register')}}">
    @csrf
    <table class="table">
        <thead>
           <tr>
               <th>会員登録</th>
           </tr>
       </thead>
       <tbody>
            <tr>
                <td>メール</td>
                <td><input class="form-control" name="email" type="email" placeholder="メールを入力してください" value=""></td>
            </tr>
            <tr>
                <td>メール確認</td>
                <td><input class="form-control" name="email1" type="email" placeholder="メールをもう一度入力してください"></td>
            </tr>
           <tr>
               <td>パスワード</td>
               <td><input class="form-control" name="password" type="password" placeholder="パスワードを入力してください"></td>
           </tr>
           <tr>
               <td>パスワード確認</td>
               <td><input class="form-control" name="password_confirmation" type="password" placeholder="パスワードをもう一度入力してください"></td>
           </tr>
           <tr>
               <td>名前</td>
               <td><input class="form-control" name="name" type="text" placeholder="名前を入力してください" value=""></td>
           </tr>
       </tbody>
       <tfoot>
           <tr>
               <td colspan="2">
                   <input class="btn btn-success btn-submit" type="submit" value="登録"/>
                   <a class="btn btn-default" href="{{ route('login')}}">ログインページへ</a>
               </td>
           </tr>
       </tfoot>
   </table>
</form>

<hr>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif