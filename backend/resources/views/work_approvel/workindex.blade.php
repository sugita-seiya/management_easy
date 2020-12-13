@extends('layouts.layout')
@include('layouts.header')

@include('layouts.header_authoritybar')

@section('content')
  <h5 class="my-3 text-center">{{$user_list->f_name.$user_list->r_name}}の勤怠一覧</h5>
  <table class="table">
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
    @foreach($work_list as $work)
      <tr class="table-bordered">
        <td>{{$work->day}}</tb>
        <td id="work-section">{{$work->work_section->section_name}}</td>
        <td>{{$work->workstart}}</td>
        <td>{{$work->workend}}</td>
        <td>{{$work->breaktime}}</td>
        <td>{{$work->total_worktime}}</td>
        <td >{{$work->remark}}</td>
      </tr>
    @endforeach
      <tr class="text-center work-list">
        <td colspan="7">
          {{ Form::model(['route' =>['work_approvel.update',$user_list->id]]) }}
              {{ Form::hidden('approval_flg',3 )}}
              {{ Form::submit('承認', ['class' => 'btn text-white pr-4 pl-4 submit-approval','id'=>'workstart-btn']) }}
          {{ Form::close() }}
        </td>
      </tr>
  </tbody>

</table>
@endsection