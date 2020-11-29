@extends('layouts.works')

@section('content')
<h5 class="my-3 text-center">システム設定</h5>
<table class="table table-bordered ">
  <thead>
    <tr class="work-index_title">
      <th scope="col">日付</th>
      <th scope="col">勤怠区分</th>
      <th scope="col">出勤時刻</th>

    </tr>
  </thead>
  <tbody>
    @foreach($user as $user_id)
      <tr id="targetTable">
        <td>{{$user_id->work_system->fixed_workstart}}</th>
        <td id="work-section">{{$user_id->work_system->fixed_workstart}}</td>
        <td>{{$user_id->work_system->fixed_workstart}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection