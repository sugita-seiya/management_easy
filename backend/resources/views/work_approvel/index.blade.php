@extends('layouts.layout')
@include('layouts.header')

@include('layouts.header_authoritybar')
@section('content')
  <h5 class="my-3 text-center">今月申請者</h5>
  <div>
    <table class="contact-index_table table table-hover">
      <thead>
        <tr class="content-index_label">
          <th scope="col">社員一覧</th>
          <th scope="col">承認状況</th>
        </tr>
      </thead>
      <tbody>
        @if($approving_user == null)
          <tr scope="row">
            <td colspan="2" class="text-center">申請している社員はいません。</td>
          </tr>
        @else
          @foreach($approving_user as $user)
            <tr>
              <td scope="row">
                <a href={{ route('work_approvel.index',['id'=>$user->id]) }}>
                  {{$user->f_name.$user->r_name}}
                </a>
              </td>
              <td scope="row">未承認</td>
            </tr>
            @endforeach
        @endif
      </tbody>
    </table>
  </div>
@endsection