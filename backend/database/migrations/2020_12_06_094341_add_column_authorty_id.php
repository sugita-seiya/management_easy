<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAuthortyId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('authorty_id');

            //追加authorty_idに外部キー制約をつける。authoritiesテーブルのidカラムを参照してそのカラムがなくなったらカスケード的に処理する。
            $table->foreign('authorty_id')
            ->references('id')
            ->on('authorities')
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
            $table->dropColumn('authorty_id');
        });
    }
}
