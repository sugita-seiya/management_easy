<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>勤怠管理システム</title>
  <!-- css読み込み -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-md navbar-light shadow-sm">
      <div class="container">
        <div class="row">
          <form method="post" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="email" value="a@a">
            <input type="hidden" name="password" value="aaaaaaaa">
            <button type="submit" class="btn text-white nav-link">管理者用ログイン</button>
          </form>
          <form method="post" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="email" value="aa@aa">
            <input type="hidden" name="password" value="aaaaaaaa">
            <button type="submit" class="btn text-white nav-link">一般社員用ログイン</button>
          </form>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
        <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            @guest
              <li class="nav-item">
                <form method="post" action="{{ route('login') }}">
                  @csrf
                  <input type="hidden" name="email" value="a@a">
                  <input type="hidden" name="password" value="aaaaaaaa">
                  <button type="submit" class="btn text-white nav-link">管理者用ログイン</button>
                </form>
              </li>
              <li class="nav-item">
                <form method="post" action="{{ route('login') }}">
                  @csrf
                  <input type="hidden" name="email" value="aa@aa">
                  <input type="hidden" name="password" value="aaaaaaaa">
                  <button type="submit" class="btn text-white nav-link">一般社員用ログイン</button>
                </form>
              </li>
            @endguest
          </ul>
        </div> -->
        <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            @guest
              <li class="nav-item">
                  <a class="nav-link text-white" href="{{ route('login') }}">{{ __('ログイン') }}</a>
              </li>
              @if (Route::has('register'))
                  <li class="nav-item">
                      <a class="nav-link text-white" href="{{ route('register') }}">{{ __('新規登録') }}</a>
                  </li>
              @endif
            @endguest
          </ul>
        </div> -->
      </div>
    </nav>

    <main class="py-4">
      @yield('content')
    </main>
  </div>
</body>

</html>