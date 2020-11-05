@extends('layout')

@section('content')
{{ Form::model('$contact_id',['route' =>['contact.update',$contact_id->id]]) }}
  <table class="table my-5 table-responsive">
    <tbody>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">日付</th>
        <td>{{$contact_id->year}}年{{$contact_id->month}}月{{$contact_id->day}}({{$week}})</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">件名</th>
        <td>{{ Form::text('subject', $contact_id->subject) }}</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">本文</th>
        <td >
        {{Form::textarea('body', $contact_id->body, ['rows' => 10,'cols' => 70])}}
        </td>
      </tr>
    </tbody>
  </table>

  <div class="text-center">
    <a href={{ route('contact.index') }}>
      <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
    </a>
    {{ Form::submit('編集', ['class' => 'btn text-white pr-4 pl-4','style' =>'background: #ef7709;']) }}
  </div>
{{ Form::close() }}
@endsection