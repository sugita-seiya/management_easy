@extends('layout')

@section('content')
<h5 class="my-3 text-center">本日の連絡一覧</h1>
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
      <tr>
        <td>19:30</td>
        <td>エンジニア1</td>
        <td>セイヤ</td>
      </tr>
      <tr>
        <td>19:30</td>
        <td>エンジニア2</td>
        <td>ジョン</td>
      </tr>
      <tr>
        <td>19:30</td>
        <td>エンジニア2</td>
        <td>ジョン</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection