@extends('layouts.layout')
@include('layouts.header')

@include('layouts.header_authoritybar')
@section('content')
  <h5 class="my-3 text-center">社員情報</h5>
  <div>
    <table class="table table-hover">
      <thead>
        <tr class="table-title">
          <th scope="col">社員番号</th>
          <th scope="col">社員名</th>
          <th scope="col">入社日</th>
        </tr>
      </thead>
      <tbody>
        @if($all_user_name == null)
          <tr scope="row">
            <td colspan="2" class="text-center">社員がいません。</td>
          </tr>
        @else
          @foreach($all_user_name as $name)
            <tr>
              <td scope="row">
                {{$name->id}}
              </td>
              <td scope="row">
                {{$name->f_name.$name->r_name}}
              </td>
              <td scope="row">
                {{$name->created_at}}
              </td>
            </tr>
            @endforeach
        @endif
      </tbody>
    </table>
  </div>
@endsection