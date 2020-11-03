@extends('layout')

@section('content')
{{ Form::open(['route' => 'contact.store']) }}
  <table class="table my-5 table-responsive">
    <tbody>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">日付</th>
        <td>{{$year}}年{{$month}}月{{$day}}日({{$week}})</td>
        {{Form::hidden('year',$year)}}
        {{Form::hidden('month',$month)}}
        {{Form::hidden('day',$day)}}
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">件名</th>
        <td>{{ Form::text('subject', null) }}</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">本文</th>
        <td >
        {{Form::textarea('body', null, ['rows' => 10,'cols' => 70])}}
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
    <a href={{ route('contact.index') }}>
      <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
    </a>
    {{ Form::submit('投稿', ['class' => 'btn text-white pr-4 pl-4','style' =>'background: #ef7709;']) }}
  </div>
{{ Form::close() }}
@endsection