<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivationCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activationCode', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phoneNum', 11);
            $table->string('code', 6);
            $table->string('sendTime', 16);
            $table->index('code');
            $table->index('phoneNum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activationCode');
    }
}
