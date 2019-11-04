<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigInteger('id_user')->unsigned()->anullable();
            $table->bigInteger('id_publication')->unsigned()->anullable();
            $table->timestamps();

            $table->primary(['id_user', 'id_publication']);

            $table->foreign('id_user')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('id_publication')->references('id')->on('publications')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
