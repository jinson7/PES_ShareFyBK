<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->bigInteger('id_follower')->anullable();
            $table->bigInteger('id_followed')->anullable();

            $table->primary(['id_follower', 'id_followed']);
            $table->foreign('id_follower')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_followed')->references('id')->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('followers');
    }
}
