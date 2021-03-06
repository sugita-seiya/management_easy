@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_workbar')
@section('content')
<h5 class="my-3 text-center">{{ $data_information['year']  }}年{{ $data_information['month'] }}月</h5>
@if($approval_flg == 4)
  <h5 class="my-3 text-danger text-center">管理者から差し戻されました。</h5>
@endif
<table class="table table-hover">
  <thead>
    <tr class="table-title table-bordered">
      <th scope="col">日付</th>
      <th scope="col">勤怠区分</th>
      <th scope="col">出勤時刻</th>
      <th scope="col">退勤時刻</th>
      <th scope="col">休憩時間</th>
      <th scope="col">合計勤務時間</th>
      <th scope="col">備考</th>
      <th scope="col">編集</th>
    </tr>
  </thead>
  <tbody>
    @foreach($user_works as $work)
      <tr class="table-bordered">
        <td>{{$work->day}}</tb>
        <td>{{$work->work_section->section_name}}</td>
        @if($work->workstart == '00:00:00')
          <td></td>
        @else
          <td>{{ date('G時i分',strtotime($work->workstart)) }}</td>
        @endif
        @if($work->workend == '00:00:00')
          <td></td>
        @else
          <td>{{ date('G時i分',strtotime($work->workend)) }}</td>
        @endif
        @if($work->breaktime == '00:00:00')
          <td></td>
        @else
          <td>{{ date('G時間',strtotime($work->breaktime))}}</td>
        @endif
        @if($work->total_worktime == '00:00:00')
          <td></td>
        @else
          <td>{{ date('G時間',strtotime($work->total_worktime)) }}</td>
        @endif
        <td>{{$work->remark}}</td>
        <td>
          <a href={{ route('work.show',['work'=>$work->id]) }}>
            編集
          </a>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<table class="table form-table">
  @if($approval_flg == '1' or $approval_flg == '4')
    <tr>
      <td class="text-center border-0">
          {{ Form::model(['route' =>['work.request']]) }}
            {{ Form::hidden('approval_flg',2 )}}
            {{ Form::hidden('login_user_id',$login_user_id)}}
            {{ Form::submit('勤怠送信', ['class' => 'btn text-white pr-4 pl-4 form-table_btn']) }}
          {{ Form::close() }}
        </td>
    </tr>
  @elseif($approval_flg == '2')
    <tr>
      <td class="text-center border-0">
        <button type="button" class="btn btn-secondary pr-4 pl-4">申請中</button>
      </td>
    </tr>
  @elseif($approval_flg == '3')
    <tr>
      <td class="text-center border-0">
        <button type="button" class="btn text-white pr-4 pl-4 form-table_btn">承認済</button>
      </td>
    </tr>
  @endif
</table>
@endsection