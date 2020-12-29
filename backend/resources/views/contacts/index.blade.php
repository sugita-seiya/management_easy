@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
  <h5 class="my-3 text-center">本日の連絡一覧</h5>
  <h5 class="my-3 text-center">{{ $data_information['year'] }}年{{ $data_information['month'] }}日{{ $data_information['day'] }}日({{ $data_information['week']  }})</h5>
  <div>
    <table class="contact-index_table table table-hover">
      <thead>
        <tr class="content-index_label">
          <th>時間</th>
          <th>件名</th>
          <th>投稿者</th>
        </tr>
      </thead>
      <tbody>
        @foreach($contacts as $contact)
          @if($contact->year.$contact->month.$contact->day == $data_information['year'].$data_information['month'].$data_information['day'])
            <tr>
              <td>{{$contact->created_at}}</td>
              <td>
                <a href={{ route('contact.show',['contact'=>$contact->id]) }}>{{$contact->subject}}</a>
              </td>
              <td>{{$contact->user->f_name.$contact->user->r_name}}</td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  </div>
@endsection