@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_workbar')
@section('content')
<table class="table table-hover">
{{ Form::model('$date_work_record ',['route' =>['work.store',$date_work_record->id ]]) }}
  <tr class="table-bordered">
    <th scope="row"  class="table-title">日付</th>
    <td>{{ $date_work_record->day }}日</tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">勤怠区分</th>
    <td>
      @if($date_work_record->work_section->section_name == '出勤')
        {{ Form::select('work_section_id', [
            '1' =>$date_work_record->work_section->section_name,
            '2' => '法定休日',
            '3' => '法定外休日',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @elseif($date_work_record->work_section->section_name == '法定休日')
        {{ Form::select('work_section_id', [
            '2' =>$date_work_record->work_section->section_name,
            '1' => '出勤',
            '3' => '法定外休日',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @else
        {{ Form::select('work_section_id', [
            '3' => $date_work_record->work_section->section_name,
            '1' => '出勤',
            '2' => '法定休日',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
    </tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">出勤時刻</th>
    <td>
      @if($worktimes_format_edit['workstart'] == '0時00分')
          出勤していません。
        @if($worktimes_format_edit['workstart'] != '0時00分')
          出勤中
        @endif
        {{ Form::hidden('workstart','0:00')}}
      @elseif($worktimes_format_edit['workstart'] == '9時00分')
        {{ Form::select('workstart', [
            $date_work_record->workstart =>$worktimes_format_edit['workstart'],
            '10:00' => '10時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @elseif($worktimes_format_edit['workstart'] == '10時00分')
        {{ Form::select('workstart', [
            $date_work_record->workstart =>$worktimes_format_edit['workstart'],
            '9:00' => '9時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
    </tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">退勤時刻</th>
    <td>
      @if($worktimes_format_edit['workend'] == '0時00分')
        @if($worktimes_format_edit['workstart'] == '0時00分')
          出勤していません。
        @else
          出勤中
        @endif
        {{ Form::hidden('workend','0:00')}}
      @elseif($worktimes_format_edit['workend'] == '18時00分')
        {{ Form::select('workend', [
            $date_work_record->workend =>$worktimes_format_edit['workend'],
            '19:00' => '19時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @elseif($worktimes_format_edit['workend'] == '19時00分')
        {{ Form::select('workend', [
            $date_work_record->workend =>$worktimes_format_edit['workend'],
            '18:00' => '18時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
    </tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">休憩時刻</th>
    <td>
      @if($worktimes_format_edit['workstart'] == '0時00分')
        出勤していません。
      @else
        1時間
      @endif
    </tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">合計勤務時刻</th>
    <td>
      @if($worktimes_format_edit['total_worktime'] == '0時間')
        @if($worktimes_format_edit['workstart'] == '0時00分')
          出勤していません。
        @else
          出勤中
        @endif
        {{ Form::hidden('total_worktime','0:00')}}
      @elseif($worktimes_format_edit['total_worktime'] == '8時間')
        {{ Form::select('total_worktime', [
            $date_work_record->total_worktime =>$worktimes_format_edit['total_worktime'],
            '9:00' => '9時間',
            '10:00' => '10時間',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @elseif($worktimes_format_edit['total_worktime'] == '9時間')
        {{ Form::select('total_worktime', [
            $date_work_record->total_worktime =>$worktimes_format_edit['total_worktime'],
            '8:00' => '8時間',
            '10:00' => '10時間',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @elseif($worktimes_format_edit['total_worktime'] == '10時間')
        {{ Form::select('total_worktime', [
            $date_work_record->total_worktime =>$worktimes_format_edit['total_worktime'],
            '8:00' => '8時間',
            '9:00' => '9時間',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
    </tb>
  </tr>
  <tr class="table-bordered">
    <th scope="row"  class="table-title">備考</th>
    <td>
      {{ Form::text('remark',$date_work_record->remark)}}
    </tb>
  </tr>
  </table>

  @if($login_user_id === $date_work_record->user_id)
    <table class="table form-table">
      <tr>
        <td class="text-center border-0">
          <a href={{ route('work.index') }}>
            <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
          </a>
          {{ Form::submit('更新', ['class' => 'btn text-white pr-4 pl-4 form-table_btn']) }}
        </td>
      </tr>
    </table>
  @endif
{{ Form::close() }}
@endsection