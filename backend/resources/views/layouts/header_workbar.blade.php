@section('workbar')
  <div class="dropdown">
    <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
    <div class="dropdown-menu ">
      <a class="dropdown-item"  href={{ route('contact.index')}}>ご連絡</a>
    </div>
  </div>
  <a class='nav-link text-white' href= @yield('title') >打刻</a>

  <a class='nav-link text-white' href={{ route('work.index') }}>勤怠一覧</a>
  <a class='nav-link text-white' href={{ route('worksystem.index') }}>システム設定</a>
@endsection
