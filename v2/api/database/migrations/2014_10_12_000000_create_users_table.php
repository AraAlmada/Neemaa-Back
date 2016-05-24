<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('firstname', 45);
            $table->string('lastname', 45);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone', 10);
            $table->string('adress', 45);
            $table->string('cp', 5);
            $table->string('city', 45);
            $table->string('country', 45);
            $table->dateTime('birthdate', 45);
            $table->string('sex', 10);
            $table->string('carnation_type', 45);
            $table->string('carnation_color', 45);
            $table->string('hair_type', 45);
            $table->string('hair_color', 45);
            $table->boolean('admin')->default(false);
            $table->boolean('active')->default(false);
            $table->string('token_activator');
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
        Schema::drop('users');
    }
}
