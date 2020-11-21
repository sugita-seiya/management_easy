<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year')->nullable(false);
            $table->integer('month')->nullable(false);
            $table->integer('day')->nullable(false);
            $table->time('workstart');
            $table->time('workend');
            $table->time('breaktime');
            $table->time('total_worktime');
            $table->string('remark');
            $table->integer('approval_flg');
            $table->unsignedBigInteger('work_section_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')  //追加user_idに外部キー制約をつける。usersテーブルのidカラムを参照してそのカラムがなくなったらカスケード的に処理する。
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('work_section_id')
            ->references('id')
            ->on('work_sections')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
