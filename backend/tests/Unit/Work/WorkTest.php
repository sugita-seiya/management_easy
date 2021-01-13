<?php
#----------------------------------------------------------------
# wrokモデルのテスト
# 実行コマンド: ./vendor/bin/phpunit tests/Unit/Work/WorkTest.php
#----------------------------------------------------------------

namespace Tests\Unit\Work;
use PHPUnit\Framework\TestCase;
use App\Work;                         //Workクラス定義

class WorkTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    # 時刻のフォーマットが以下の内容に変更されている事
    # HH:MM:SS->HH時間
    # HH:MM:SS->HH時MM分
    public function test_Work_Time_Format()
    {
        # 時刻のフォーマット変更(HH:MM:SS->HH時間、HH:MM:SS->HH時MM分)
        $work                  = new Work;
        $worktimes_format_edit = $work->Work_Time_Format('9:00','18:00','1:00','8:00');
        // dd($worktimes_format_edit['workstart']);
        $this->assertEquals($worktimes_format_edit['workstart'],'9時00分');
        $this->assertEquals($worktimes_format_edit['workend'],'18時00分');
        $this->assertEquals($worktimes_format_edit['breaktime'],'1時間');
        $this->assertEquals($worktimes_format_edit['total_worktime'],'8時間');
    }

    #  勤怠の合計時間が以下の内容で計算されている事
    #  合計勤務時間 = 勤怠終了時刻 - 勤怠開始時刻
    public function test_Total_WorkTime()
    {
        #  勤怠時間の計算(合計勤務時間 = 終了時間-開始時間-休憩時間)
        $work           = new Work;
        $total_worktime = $work->Total_WorkTime('9:00','18:00','1:00');
        // dd($total_worktime);
        $this->assertEquals($total_worktime,'8:00');
    }
}
