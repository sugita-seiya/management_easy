@extends('layout')

@section('content')
  <h5 class="my-3 text-center">本日の連絡一覧</h5>
  <h5 class="my-3 text-center">{{$today_date[0]}}年{{$today_date[1]}}日{{$today_date[2]}}日({{$today_date[3]}})</h5>
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
          @if($contact->year.$contact->month.$contact->day == $today)
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