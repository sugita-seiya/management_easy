@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_contactbar')
@section('content')
  {{ Form::open(['route' => 'contact.store']) }}
    <table class="table my-5">
      <tbody class="table-responsive">
        <tr class="table-bordered">
          <th class="contact-new_label pr-5">日付</th>
          <td>{{ $data_information['year']  }}年{{ $data_information['month'] }}月{{ $data_information['day'] }}日({{ $data_information['week'] }})</td>
            {{Form::hidden('year',$data_information['year'] )}}
            {{Form::hidden('month',$data_information['month'])}}
            {{Form::hidden('day',$data_information['day'])}}
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
          <td class="text-center contact-form_btn">
            <a href={{ route('contact.index') }}>
              <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
            </a>
            {{ Form::submit('投稿', ['class' => 'btn text-white pr-4 pl-4 contact-new_submit']) }}
          </td>
        </tr>
      </tbody>
    </table>
  {{ Form::close() }}
@endsection