<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liveChats', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->unsignedInteger('userId');
            $table->unsignedInteger('videoId');
            $table->string('username');
            $table->string('userPic', 300);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liveChats');
    }
}
