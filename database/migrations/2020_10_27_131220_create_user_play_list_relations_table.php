<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPlayListRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userPlayListRelations', function (Blueprint $table) {
            $table->unsignedInteger('playListId');
            $table->unsignedInteger('videoId');
            $table->unsignedInteger('row')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('userPlayListRelations');
    }
}
