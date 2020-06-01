<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('userId');
            $table->string('categoryId')->nullable();
            $table->string('description')->nullable();
            $table->string('code');
            $table->string('sTime');
            $table->string('sDate');
            $table->tinyInteger('isLive')->default(1);
            $table->tinyInteger('haveChat')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lives');
    }
}
