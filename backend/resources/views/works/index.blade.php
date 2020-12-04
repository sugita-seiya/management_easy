@extends('layouts.works')
@include('layouts.header')
@section('content')
<h5 class="my-3 text-center">{{$date[0]}}年{{$date[1]}}月</h5>
<table class="table table-bordered ">
  <thead>
    <tr class="work-index_title">
      <th scope="col">日付</th>
      <th scope="col">勤怠区分</th>
      <th scope="col">出勤時刻</th>
      <th scope="col">退勤時刻</th>
      <th scope="col">休憩時間</th>
      <th scope="col">合計勤務時間</th>
      <th scope="col">備考</th>
    </tr>
  </thead>
  <tbody>
    @foreach($user_works as $work)
      <tr id="targetTable">
        <td>{{$work->day}}</tb>
        <td id="work-section">{{$work->work_section->section_name}}</td>
        <td>{{$work->workstart}}</td>
        <td>{{$work->workend}}</td>
        <td>{{$work->breaktime}}</td>
        <td>{{$work->total_worktime}}</td>
        <td >{{$work->remark}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection