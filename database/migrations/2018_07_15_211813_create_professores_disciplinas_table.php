<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessoresDisciplinasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professores_disciplinas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('professores_id')->unsigned()->nullable();
            $table->foreign('professores_id')
                ->references('id')
                ->on('professores')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('disciplinas_id')->unsigned()->nullable();
            $table->foreign('disciplinas_id')
                ->references('id')
                ->on('disciplinas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

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
        Schema::dropIfExists('professores_disciplinas');
    }
}
