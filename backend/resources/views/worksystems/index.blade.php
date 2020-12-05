@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_workbar')
@section('content')
  <h5 class="my-3 text-center">システム設定</h5>
  <table class="table work-system">
    @foreach($user as $user)
      <tr class="table-bordered">
        <th scope="row"  class="work-index_title">出勤時間</th>
        <td>{{ date('G時i分',strtotime($user->work_system->fixed_workstart)) }}</tb>
      </tr>
      <tr class="table-bordered">
        <th scope="row"  class="work-index_title">退勤時間</th>
        <td>{{ date('G時i分',strtotime($user->work_system->fixed_workend)) }}</tb>
      </tr>
      <tr class="table-bordered">
        <th scope="row"  class="work-index_title ">休憩時間</th>
        <td>{{ date('G時間',strtotime($user->work_system->fixed_breaktime)) }}</tb>
      </tr>
    @endforeach

    <tr>
      <th class="pr-5"></th>
      <td class="text-left">
        <a href={{ route('worksystem.edit',['worksystem'=>$user->work_system_id]) }}>
          <button type="button" class="btn text-white pr-4 pl-4 work-system_time">
            編集
          </button>
        </a>
      </td>
    </tr>
  </table>
@endsection