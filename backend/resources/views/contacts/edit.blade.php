@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
{{ Form::model('$contact_record',['route' =>['contact.update',$contact_record->id]]) }}
  @method('PUT')
  <table class="table my-5 table-hover">
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">日付</th>
        <td>{{ $contact_record->year }}年{{ $contact_record->month }}月{{ $contact_record->day }}({{ $data_information['week'] }})</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">件名</th>
        <td>{{ Form::text('subject', $contact_record->subject) }}</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">本文</th>
        <td >
        {{Form::textarea('body', $contact_record->body, ['rows' => 10,'cols' => 50])}}
        </td>
      </tr>
      @if($login_user_id === $contact_record->user_id)
        <tr>
          <th class="pr-5"></th>
          <td class="text-center">
            <a href={{ route('contact.show',['contact'=>$contact_record->id]) }}>
              <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
            </a>
            {{ Form::submit('更新', ['class' => 'btn text-white pr-4 pl-4','style' =>'background: #ef7709;']) }}
          </td>
        </tr>
      @endif
  </table>
{{ Form::close() }}
@endsection