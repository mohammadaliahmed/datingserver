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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address');
            $table->text('picUrl')->nullable();
            $table->text('fcmKey')->nullable();
            $table->string('birthday')->nullable();
            $table->string('socialLogin')->nullable();
            $table->string('pictureVerified')->default(0);
            $table->string('emailVerified')->default(0);
            $table->string('phoneVerified')->default(0);
            $table->text('driverLicense')->nullable();
            $table->text('emailCode')->nullable();
            $table->text('phoneNumber')->nullable();
            $six_digit_random_number = mt_rand(100000, 999999);
            $table->string('verificationCode')->default($six_digit_random_number);
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
