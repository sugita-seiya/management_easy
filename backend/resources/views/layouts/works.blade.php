<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <!-- userログイン機能 -->
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css読み込み -->
    <link rel="stylesheet" href='{{ asset("css/style.css")}}'>
    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> -->
    <!-- bootstrap読み込み -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>勤怠管理システム</title>
    <!-- userログイン機能読み込み -->
    <script src='{{ asset("js/app.js") }}' defer></script>
  </head>
  <body>
    <nav class='navbar navbar-expand-md mb-5'>
      <div class="dropdown">
        <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
        <div class="dropdown-menu ">
          <a class="dropdown-item"  href={{ route('contact.index')}}>ご連絡</a>
        </div>
      </div>
      <a class='nav-link text-white' href={{ route('work.index') }}>勤怠一覧</a>
      <a class='nav-link text-white' href={{ route('worksystem.index') }}>システム設定</a>

      <!-- ここからログインユーザーの表示 -->
      <!-- layouts/app.blade.phpからコピー -->
      <ul class="navbar-nav ml-auto">
        <!-- ユーザー認証されていない場合 -->
        @guest
          <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @if (Route::has('register'))
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
              </li>
          @endif
        <!-- ユーザー認証されている場合 -->
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->f_name}} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ url('login') }}"
                  onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                  {{ __('ログアウト') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </nav>
    <div class='container'>
      @yield('content')
    </div>
    <!-- jQuery読み込み -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <!-- PopperのJS読み込み -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script> -->
    <!-- BootstrapのJS読み込み -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->
    <script src="{{ asset('/js/work-edit.js') }}"></script>
    <script src="{{ asset('/js/work-index.js') }}"></script>
  </body>
</html>