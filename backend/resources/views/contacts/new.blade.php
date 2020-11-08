@extends('layout')

@section('content')
  {{ Form::open(['route' => 'contact.store']) }}
    <table class="table my-5">
      <tbody class="table-responsive">
        <tr class="table-bordered">
          <th class="contact-new_label pr-5">日付</th>
          <td>{{$today_date[0]}}年{{$today_date[1]}}日{{$today_date[2]}}日({{$today_date[3]}})</td>
            {{Form::hidden('year',$today_date[0])}}
            {{Form::hidden('month',$today_date[1])}}
            {{Form::hidden('day',$today_date[2])}}
        </tr>
        <tr class="table-bordered">
          <th class="contact-new_label pr-5">件名</th>
          <td>{{ Form::text('subject', null) }}</td>
        </tr>
        <tr class="table-bordered">
          <th class="contact-new_label pr-5">本文</th>
          <td >
          {{Form::textarea('body', null, ['rows' => 10,'cols' => 70])}}
          </td>
        </tr>
        <tr>
          <th class="pr-5"></th>
          <td class="text-center">
            <a href={{ route('contact.index') }}>
              <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
            </a>
            {{ Form::submit('投稿', ['class' => 'btn text-white pr-4 pl-4','style' =>'background: #ef7709;']) }}
          </td>
        </tr>
      </tbody>
    </table>
  {{ Form::close() }}
@endsection