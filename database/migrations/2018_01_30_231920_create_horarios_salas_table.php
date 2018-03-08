<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios_salas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('ensalamentos_id')->unsigned();
            $table->foreign('ensalamentos_id')
                ->references('id')
                ->on('ensalamentos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->integer('salas_id')->unsigned();
            $table->foreign('salas_id')
                ->references('id')
                ->on('salas')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->integer('horarios_id')->unsigned();
            
            $table->foreign('horarios_id')
                ->references('id')
                ->on('horarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unique(['salas_id','ensalamentos_id','horarios_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios_salas');
    }
}
