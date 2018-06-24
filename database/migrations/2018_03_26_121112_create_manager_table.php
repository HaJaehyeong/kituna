<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manager', function (Blueprint $table) {
            $table->string('manager_id', 16)->primary();
            $table->string('manager_password', 16);
            $table->string('management_company', 45);
            $table->string('leader', 20);
            $table->string('phone', 11);
            $table->string('mail', 45);
            $table->string('address', 100);
            $table->string('fax', 20)->nullable()->default(NULL);
            $table->string('sign_path', 45)->nullable()->default(NULL);
            
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
        Schema::dropIfExists('manager');
    }
}
