<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order', function (Blueprint $table) {
            $table->increments('jo_id');
            $table->integer('sp_id')->unsigned();
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('sp_id')
                  ->references('sp_id')->on('supplementer');

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
        Schema::dropIfExists('job_order');
    }
}
