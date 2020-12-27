@section('authoritybar')
  <div class="dropdown">
    <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
    <div class="dropdown-menu ">
      <a class="dropdown-item" href={{ route('work.index') }}>勤怠</a>
      <a class="dropdown-item"  href={{ route('contact.index')}}>ご連絡</a>
    </div>
  </div>
  <a class='nav-link text-white' href={{ route('user_all.index') }}>全社員一覧</a>
  <a class='nav-link text-white' href={{ route('user_approvel.index') }}>勤怠申請者</a>
@endsection