<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnsalamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ensalamentos', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('turmas_id')->unsigned();
            $table->foreign('turmas_id')->references('id')->on('turmas')->onDelete('cascade');

            $table->integer('disciplinas_id')->unsigned();
            $table->foreign('disciplinas_id')->references('id')->on('disciplinas')->onDelete('cascade');

            $table->integer('horarios_id')->unsigned();
            $table->foreign('horarios_id')->references('id')->on('horarios')->onDelete('cascade');

            $table->integer('salas_id')->unsigned();
            $table->foreign('salas_id')->references('id')->on('salas')->onDelete('cascade');

            $table->enum('dia', [0,1,2,3,4,5,6]);
            $table->tinyInteger('horario')->unsigned();

            $table->unique(['horarios_id', 'salas_id', 'dia', 'horario'], 'unique_all');

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
        Schema::dropIfExists('ensalamentos');
    }
}
