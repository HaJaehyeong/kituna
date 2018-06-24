<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplementerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplementer', function (Blueprint $table) {
            $table->increments('sp_id');
            $table->string('sp_login_id', 16)->unique();
            $table->string('sp_password', 16);
            $table->string('sp_name', 20);
            $table->string('sp_phone', 20);
            $table->string('sp_mail', 45);
            $table->string('sp_address', 100)->nullable()->default(NULL);
            $table->string('sp_img_path', 45)->nullable()->default(NULL);

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
        Schema::dropIfExists('supplementer');
    }
}
