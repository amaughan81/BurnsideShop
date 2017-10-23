<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('auth');
            $table->integer('sims_id');
            $table->string('forename');
            $table->string('surname');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('role');
            $table->char('gender', 1);
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('deleted')->default(0);
            $table->dateTime('deleted_date')->nullable();
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
