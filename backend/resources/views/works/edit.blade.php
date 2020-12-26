@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_workbar')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-center" style="background:#e9e1de;">{{('出退勤') }}</div>
        <div class="card-body text-center">
          <div class="work-day mb-3">
            {{$today_date[0]}}年{{$today_date[1]}}月{{$today_date[2]}}日({{$today_date[3]}})
          </div>
          <div class="work-time mb-3">
            <h1>
              {{$today_date[4]}}
            </h1>
          </div>
          @if($approval_flg =='1')
            @if($login_user_id === $work_record->user_id)
              <div class="d-flex justify-content-center attendance">
                <div class="mb-3 mr-2">
                  {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                    @method('PUT')
                    {{ Form::hidden('workstart',$user_record[0]->work_system->fixed_workstart )}}
                    {{ Form::submit('出勤', ['class' => 'work-start btn text-white pr-4 pl-4','id'=>'workstart-btn']) }}
                  {{ Form::close() }}
                </div>
                @if($work_record->workstart == '00:00:00')
                  <div hidden id="work-start_hours"></div>
                @else
                  <div hidden id="work-start_hours">{{$work_record->workstart}}</div>
                @endif
                <div class="mb-3">
                  {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                    @method('PUT')
                    {{ Form::hidden('workend', $user_record[0]->work_system->fixed_workend)}}
                    {{ Form::submit('退勤', ['class' => 'btn text-white pr-4 pl-4 btn-secondary','id'=>'workend-btn']) }}
                  {{ Form::close() }}
                  @if($work_record->workend == '00:00:00')
                    <div hidden id="work-end_hours"></div>
                  @else
                    <div hidden id="work-end_hours">{{$work_record->workend}}</div>
                  @endif
                </div>
              </div>
            @endif
          @elseif($approval_flg =='2')
            <div class="d-flex justify-content-center attendance">
              <button type="button" class="btn btn-secondary pr-4 pl-4">勤怠申請中</button>
            </div>
          @elseif($approval_flg =='3')
            <div class="d-flex justify-content-center attendance">
              <button type="button" class="btn btn-secondary pr-4 pl-4">勤怠承認されました(今月分)</button>
            </div>
          @elseif($approval_flg =='4')
            <div class="d-flex justify-content-center attendance">
              <button type="button" class="btn btn-secondary pr-4 pl-4">勤怠を修正してください(今月分)</button>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection