<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoPlaceRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videoPlaceRelations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('videoId');
            $table->integer('kindPlaceId'); // 0 city , -1 state
            $table->unsignedInteger('placeId');

            $table->foreign('videoId')->references('id')->on('videos')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videoPlaceRelations');
    }
}
