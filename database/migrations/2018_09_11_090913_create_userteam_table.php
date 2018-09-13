<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserteamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userteam', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer("teams_id")->unsigned();
            $table->integer("user_id")->unsigned();
           
        });

         Schema::table('userteam', function (Blueprint $table) {
            $table->foreign('teams_id')->references('id')->on('teams')->onDelete('cascade');
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
        Schema::dropIfExists('userteam');
    }
}
