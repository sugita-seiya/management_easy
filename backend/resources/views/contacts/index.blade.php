@extends('layout')

@section('content')
<h5 class="my-3 text-center">本日の連絡一覧</h5>
<div class="">
  <table class="table table-hover" style="font-size : 12px;">
    <thead>
      <tr style="background: #e9e1de;">
        <th>時間</th>
        <th>件名</th>
        <th>投稿者</th>
      </tr>
    </thead>
    <tbody>
      @foreach($contacts as $contact)
        <tr>
          <td>{{$contact->created_at}}</td>
          <td><a href="">{{$contact->subject}}</a></td>
          <td>セイヤ</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection