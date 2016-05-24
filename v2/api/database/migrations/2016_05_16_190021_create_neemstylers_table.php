<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeemstylersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neemstylers', function (Blueprint $table) {
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
            $table->string('society', 45);
            $table->boolean('home');
            $table->boolean('saloon');
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
        Schema::drop('neemstylers');
    }
}
