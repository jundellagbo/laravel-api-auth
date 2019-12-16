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
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->string('email', 100);
            $table->string('gender', 6)->nullable();
            $table->string('country')->nullable();
            $table->string('birthDate')->nullable();
            $table->string('watsappNumber', 15)->nullable();
            $table->string('phoneNumber', 15)->nullable();
            $table->string('passportNumber', 50)->nullable();
            $table->string('visaNumber', 50)->nullable();
            $table->string('accessCardNumber', 50)->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
