<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userId');
            $table->string('code', 10);
            $table->string('title');
            $table->string('description', 255)->nullable();
            $table->string('video');
            $table->unsignedInteger('categoryId');
            $table->string('subtitle')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('duration', 15)->nullable();
            $table->integer('seen')->default(0);
            $table->tinyInteger('confirm')->default(0);
            $table->tinyInteger('state')->default(0);
            $table->timestamps();

//            $table->foreign('categoryId')->references('id')->on('videoCategories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
