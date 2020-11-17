@extends('layouts.works')

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
                        <div class="d-flex justify-content-center">
                            <div class="work-form mb-3 mr-2">
                                {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                                    @method('PUT')
                                    {{Form::hidden('workstart',$today_date[4] )}}
                                    {{ Form::submit('出勤', ['class' => 'btn text-white pr-4 pl-4','style' =>'background: #ef7709;']) }}
                                {{ Form::close() }}
                            </div>
                            <div class="work-form mb-3">
                                {{ Form::model('$work',['route' =>['work.update',$work]]) }}
                                    @method('PUT')
                                    {{Form::hidden('workend', $today_date[4])}}
                                    {{ Form::submit('退勤', ['class' => 'btn text-white pr-4 pl-4 btn-secondary']) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection