<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('year')->nullable(false);
            $table->integer('month')->nullable(false);
            $table->integer('day')->nullable(false);
            $table->string('subject')->nullable(false);
            $table->text('body')->nullable(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')  //追加user_idに外部キー制約をつけますよ。usersテーブルのidカラムを参照してそのカラムがなくなったらカスケード的に処理します。
            ->references('id')
            ->on('users')
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
        Schema::dropIfExists('contacts');
    }
}
