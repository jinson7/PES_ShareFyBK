<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_user')->unsigned()->anullable();
            $table->bigInteger('id_publication')->unsigned()->anullable();
            $table->timestamp('date')->anullable();
            $table->text('text')->anullable();
            $table->timestamps();

            $table->unique(['id_user', 'id_publication', 'date']);
            
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
        Schema::dropIfExists('comments');
    }
}
