<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_systems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('fixed_workstart')->nullable(false);
            $table->time('fixed_workend')->nullable(false);
            $table->time('fixed_breaktime')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_systems');
    }
}
