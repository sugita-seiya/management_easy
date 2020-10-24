@extends('layout')

@section('content')
<table class="table table-bordered my-5">
  <tbody>
    <tr>
      <td class="" >日付</td>
      <td>2020.10.24</td>
    </tr>
    <tr>
      <td class="">件名</td>
      <td>遅刻</td>
    </tr>
    <tr>
      <td class="">本文</td>
      <td>すいません。１０分遅刻します。</td>
    </tr>
  </tbody>
</table>
<div class="">
  <button type="button" class="btn btn-light">戻る</button>
  <button type="button" class="btn btn-warning">登録</button>
</div>



@endsection