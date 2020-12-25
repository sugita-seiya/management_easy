@extends('layouts.layout')
@include('layouts.header')
@include('layouts.header_workbar')
@section('content')
<h5 class="my-3 text-center">システム設定</h5>
{{ Form::model('$worksystem_id',['route' =>['worksystem.update',$worksystem_id]]) }}
@method('PUT')
<table class="table work-system">
    <tr class="table-bordered">
      <th scope="row"  class="work-index_title">出勤時間</th>
      <td>
      @if ($worktimes[0] == '9時00分')
        {{ Form::select('fixed_workstart', [
          $worksystem_id->fixed_workstart  => $worktimes[0],
          '10:00' => '10時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
      @if ($worktimes[0] == '10時00分')
        {{ Form::select('fixed_workstart', [
          $worksystem_id->fixed_workstart  => $worktimes[0],
          '9:00'  => '9時00分',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
      </tb>
    </tr>
    <tr class="table-bordered">
      <th scope="row"  class="work-index_title">退勤時間</th>
      <td>
      @if ($worktimes[1] == '18時00分')
        {{ Form::select('fixed_workend', [
          $worksystem_id->fixed_workend  =>$worktimes[1],
          '19:00' => '19時00分'
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
      @if ($worktimes[1] == '19時00分')
        {{ Form::select('fixed_workend', [
          $worksystem_id->fixed_workend  => $worktimes[1],
          '18:00' => '18時00分'
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2 '])
        }}
      @endif
      </tb>
    </tr>
    <tr class="table-bordered">
      <th scope="row"  class="work-index_title">休憩時間</th>
      <td>
      @if ($worktimes[2] == '1時間')
        {{ Form::select('fixed_breaktime',[
          $worksystem_id->fixed_breaktime => $worktimes[2],
          '2:00'  => '2時間',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2'])
        }}
      @endif
      @if ($worktimes[2] == '2時間')
        {{ Form::select('fixed_breaktime',[
          $worksystem_id->fixed_breaktime => $worktimes[2],
          '1:00'  => '1時間',
          ], null, ['class' => 'pt-2 pb-2 pr-2 pl-2'])
        }}
      @endif
      </tb>
    </tr>

  <tr>
    <th class="pr-5"></th>
    <td class="text-left">
      <a href={{ route('worksystem.index') }}>
        <button type="button" class="btn btn-secondary pr-4 pl-4">戻る</button>
      </a>
          {{ Form::submit('更新', ['class' => 'btn text-white pr-4 pl-4 work-system_time']) }}
    </td>
  </tr>
</table>
{{ Form::close() }}
@endsection