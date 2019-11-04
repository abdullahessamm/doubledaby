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
            $table->bigIncrements('id');
            $table->enum('level', ['admin', 'user']);
            $table->string('full_name');
            $table->string('username')->unique();
            $table->string('cart', 150)->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique()->nullable();
            $table->enum('gender', ['male','female']);
            $table->integer('verify_phone_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_added_at')->nullable();
            $table->string('phone_verified_at')->nullable();
            $table->string('password');
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
