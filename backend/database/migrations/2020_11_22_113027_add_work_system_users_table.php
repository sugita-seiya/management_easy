<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkSystemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('work_system_id');

            //追加work_system_idに外部キー制約をつける。work_systemsテーブルのidカラムを参照してそのカラムがなくなったらカスケード的に処理する。
            $table->foreign('work_system_id')
            ->references('id')
            ->on('work_systems')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('work_system_id');
        });
    }
}
