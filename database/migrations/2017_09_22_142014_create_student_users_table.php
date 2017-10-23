<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('year_group')->nullable();
            $table->char('form',1)->nullable();
            $table->date('dob')->nullable()->default(NULL);
            $table->string('upn',15)->nullable();
            $table->string('adno',6)->nullable();
            $table->tinyInteger('fsm')->default(0)->nullable();
            $table->tinyInteger('gt')->default(0)->nullable();
            $table->tinyInteger('eal')->default(0)->nullable();
            $table->tinyInteger('scei')->default(0)->nullable();
            $table->tinyInteger('fsme')->default(0)->nullable();
            $table->tinyInteger('care')->default(0)->nullable();
            $table->tinyInteger('ppi')->default(0)->nullable();
            $table->string('sen',4)->nullable();
            $table->string('need_type',6)->nullable();
            $table->tinyInteger('ccc')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_users');
    }
}
