@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
  <table class="table table-hover">
    <tr class="table-bordered">
      <th class="contact-new_label pr-5">日付</th>
      <td>{{ $contact_record->updated_at }}({{ $data_information['week'] }})</td>
    </tr>
    <tr class="table-bordered">
      <th class="contact-new_label pr-5">投稿者</th>
      <td>{{$contact_record->user->f_name.$contact_record->user->r_name}}</td>
    </tr>
    <tr class="table-bordered">
      <th class="contact-new_label pr-5">件名</th>
      <td>{{$contact_record->subject}}</td>
    </tr>
    <tr class="table-bordered">
      <th class="contact-new_label pr-5" >本文</th>
      <td>
        <textarea disabled="disabled" name="mytextarea"  cols="50" rows="10" class="contact-show_body bg-white">{{$contact_record->body}}</textarea>
      </td>
    </tr>
  </table>
  @if($login_user_id === $contact_record->user_id)
    <table class="table form-table">
      <tr>
        <td class="text-right border-0">
          {{ Form::open(['method' => 'delete', 'route' => ['contact.destroy', $contact_record->id]]) }}
            {{ Form::submit('[× 削除する]', ['class' => 'btn clear-decoration contact-delete']) }}
          {{ Form::close() }}
        </td>
      </tr>
      <tr>
        <td class="text-center border-0">
            <a href={{ route('contact.index') }}>
              <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
            </a>
            <a href={{ route('contact.edit',['contact'=>$contact_record->id]) }}>
              <button type="button" class="btn text-white pr-4 pl-4 form-table_btn">編集</button>
            </a>
        </td>
      </tr>
    </table>
  @endif
@endsection