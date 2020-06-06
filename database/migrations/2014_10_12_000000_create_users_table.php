<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('username', 40)->unique();
            $table->string('password');
            $table->string('email', 100);
            $table->string('picture')->nullable();
            $table->string('link')->nullable();
            $table->string('phone', 11)->nullable();
            $table->boolean('status');
            $table->boolean('uploadPhoto');
            $table->unsignedInteger('cityId');
            $table->index('cityId')->default(6);
            $table->integer('level');
            $table->string('invitationCode', 6);
            $table->longText('api_token')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
