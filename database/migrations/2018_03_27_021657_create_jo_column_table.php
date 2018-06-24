<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jo_column', function (Blueprint $table) {
            $table->increments('jc_id');
            $table->integer('jo_id')->unsigned();
            $table->integer('vd_id')->unsigned();
            $table->string('vd_name', 30);
            $table->string('sp_login_id', 16);
            $table->string('drink_name', 45);
            $table->string('drink_path', 45);
            $table->integer('sp_val')->unsigned();
            $table->integer('drink_line')->unsigned();
            $table->string('note', 45)->nullable();
            $table->integer('sp_check')->unsigned();
            
            $table->foreign('jo_id')
                  ->references('jo_id')->on('job_order');

            $table->engine  = 'InnoDB';
            $table->charset = 'utf8';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jo_column');
    }
}
