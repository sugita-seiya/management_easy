@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
  <table class="table my-5">
    <tbody class="table-responsive">
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">日付</th>
        <td>{{$contact_id->updated_at}}({{$today_date[3]}})</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">投稿者</th>
        <td>{{$contact_id->user->f_name.$contact_id->user->r_name}}</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5">件名</th>
        <td>{{$contact_id->subject}}</td>
      </tr>
      <tr class="table-bordered">
        <th class="contact-new_label pr-5" >本文</th>
        <td>
          <textarea disabled="disabled" name="mytextarea"  cols="70" rows="10" class="contact-show_body bg-white">{{$contact_id->body}}</textarea>
        </td>
      </tr>
      <tr class="border-0">
        <th class="pr-5"></th>
        <td class="text-right show-from">
          @if($user->id == $contact_id->user_id)
            {{ Form::open(['method' => 'delete', 'route' => ['contact.destroy', $contact_id->id]]) }}
              {{ Form::submit('[× 削除する]', ['class' => 'btn clear-decoration contact-delete']) }}
            {{ Form::close() }}
            <div class="text-center">
              <a href={{ route('contact.index') }}>
                <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
              </a>
              <a href={{ route('contact.edit',['contact'=>$contact_id->id]) }}>
                <button type="button" class="btn text-white pr-4 pl-4 contact-edit">編集</button>
              </a>
            </div>
          @endif
        </td>
      </tr>
    </tbody>
  </table>
@endsection