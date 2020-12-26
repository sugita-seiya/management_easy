@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
  <table class="table my-5">
    <tbody class="table-responsive">
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">日付</th>
        <td>{{$contact_record->updated_at}}({{$today_date[3]}})</td>
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
          <textarea disabled="disabled" name="mytextarea"  cols="70" rows="10" class="contact-show_body bg-white">{{$contact_record->body}}</textarea>
        </td>
      </tr>
      <tr class="border-0">
        <th class="pr-5"></th>
        <td class="text-right show-from">
          @if($login_user_id === $contact_record->user_id)
            {{ Form::open(['method' => 'delete', 'route' => ['contact.destroy', $contact_record->id]]) }}
              {{ Form::submit('[× 削除する]', ['class' => 'btn clear-decoration contact-delete']) }}
            {{ Form::close() }}
            <div class="text-center">
              <a href={{ route('contact.index') }}>
                <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
              </a>
              <a href={{ route('contact.edit',['contact'=>$contact_record->id]) }}>
                <button type="button" class="btn text-white pr-4 pl-4 contact-edit">編集</button>
              </a>
            </div>
          @endif
        </td>
      </tr>
    </tbody>
  </table>
@endsection