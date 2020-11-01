@extends('layout')

@section('content')
{{ Form::open(['route' => 'contact.store']) }}
  <table class="table table-bordered my-5 ">
    <tbody>
      <tr>
        <td class="contact-new_label pr-5 ">日付</td>
        <td>{{$year}}年{{$month}}月{{$day}}日({{$week}})</td>
        {{Form::hidden('year',$year)}}
        {{Form::hidden('month',$month)}}
        {{Form::hidden('day',$day)}}
      </tr>
      <tr>
        <td class="contact-new_label pr-5">件名</td>
        <td>{{ Form::text('subject', null) }}</td>
      </tr>
      <tr>
        <td class="contact-new_label pr-5 ">本文</td>
        <td>
        {{Form::textarea('body', null, ['rows' => 10,'cols' => 70])}}
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
    <a href={{ route('contact.index') }}>
      <button type="button" class="btn btn-light">戻る</button>
    </a>
    {{ Form::submit('投稿', ['class' => 'btn btn-warning']) }}
  </div>
{{ Form::close() }}
@endsection