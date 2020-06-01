<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videoFeedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->unsignedInteger('videoId');
            $table->unsignedInteger('commentId')->nullable();
            $table->tinyInteger('like')->nullable();// 1 like, -1 dislike
            $table->timestamps();

            $table->foreign('videoId')->references('id')->on('videos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('commentId')->references('id')->on('videoComments')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videoFeedbacks');
    }
}
