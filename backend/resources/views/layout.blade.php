<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- css読み込み -->
    <link rel="stylesheet" href="{{asset('css/contacts/layout.css')}}">
    <!-- bootstrap読み込み -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- jQuery読み込み -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- PopperのJS読み込み -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <!-- BootstrapのJS読み込み -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <title>勤怠管理システム</title>
  </head>
  <body>
  <style>

    html{
      font-size : 14px;
    }
  </style>
    <nav class='navbar navbar-expand-md' style="background-color:#5e3b1b;">
    <div class="dropdown">
      <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
      <div class="dropdown-menu ">
        <a class="dropdown-item" href="#">勤怠</a>
        <a class="dropdown-item"  href="#">ご連絡</a>
      </div>
    </div>
    <a class='nav-link text-white' href="#">ご連絡一覧</a>
    <a class='nav-link text-white' href={{route('contact.new')}}>連絡書き込み</a>

    <!-- ここからログインユーザーの表示 -->
    <!-- layouts/app.blade.phpからコピー -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">

        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        セイヤ<span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                    </div>
                </li>
        </ul>
    </div>

    </nav>
    <div class='container'>
      @yield('content')
    </div>
  </body>
</html>