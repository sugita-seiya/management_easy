@extends('layouts.layout')
@include('layouts.header')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-center" style="background:#e9e1de;">{{('取得エラー') }}</div>
        <div class="card-body text-center">
          @php
            echo $errer_messege;
          @endphp
        </div>
      </div>
    </div>
  </div>
</div>
@endsection