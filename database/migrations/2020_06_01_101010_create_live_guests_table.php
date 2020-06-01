<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liveGuests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('videoId');
            $table->string('name');
            $table->string('action')->nullable();
            $table->mediumText('text')->nullable();
            $table->string('pic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liveGuests');
    }
}
