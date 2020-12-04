@extends('layouts.works')
@include('layouts.header')
@section('title',route('work.edit',['work'=>$work->id]))
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
                        <div class="d-flex justify-content-center attendance">
                            <div class="mb-3 mr-2">
                                {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                                    @method('PUT')
                                    {{ Form::hidden('workstart',$user[0]->work_system->fixed_workstart )}}
                                    {{ Form::submit('出勤', ['class' => 'work-start btn text-white pr-4 pl-4','id'=>'workstart-btn']) }}
                                {{ Form::close() }}
                            </div>
                                @if($work->workstart == '00:00:00')
                                    <div hidden id="work-start_hours"></div>
                                @else
                                    <div hidden id="work-start_hours">{{$work->workstart}}</div>
                                @endif
                            <div class="mb-3">
                                {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                                    @method('PUT')
                                    {{ Form::hidden('workend', $user[0]->work_system->fixed_workend)}}
                                    {{ Form::submit('退勤', ['class' => 'btn text-white pr-4 pl-4 btn-secondary','id'=>'workend-btn']) }}
                                {{ Form::close() }}
                                @if($work->workend == '00:00:00')
                                    <div hidden id="work-end_hours"></div>
                                @else
                                    <div hidden id="work-end_hours">{{$work->workend}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
