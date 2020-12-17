@section('workbar')
  <div class="dropdown">
    <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
    <div class="dropdown-menu ">
      <a class="dropdown-item"  href={{ route('contact.index')}}>ご連絡</a>
      @if($login_user_authortyid == $admin_user)
        <a class="dropdown-item" href={{ route('user_approvel.index') }}>管理者用</a>
      @elseif($login_user_authortyid == $general_user)
      @else
      @endif
    </div>
  </div>
  <a class='nav-link text-white' href={{ route('work.edit', ['work'=>$work] )}} >打刻</a>
  <a class='nav-link text-white' href={{ route('work.index') }}>勤怠一覧</a>
  <a class='nav-link text-white' href={{ route('worksystem.index') }}>システム設定</a>
@endsection
