@section('contactbar')
  <div class="dropdown">
    <button type="button" class="nav-link btn dropdown-toggle text-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">勤怠管理</button>
    <div class="dropdown-menu ">
      <a class="dropdown-item" href={{ route('work.index') }}>勤怠</a>
      @if($authortyid_information['login_user_authortyid'] == $authortyid_information['admin_user'])
        <a class="dropdown-item" href={{ route('user_approvel.index') }}>管理者用</a>
      @endif
    </div>
  </div>
  <a class='nav-link text-white' href={{ route('contact.index') }}>ご連絡一覧</a>
  <a class='nav-link text-white' href={{ route('contact.create') }}>連絡書き込み</a>
@endsection