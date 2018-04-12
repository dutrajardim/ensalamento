<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('disciplinas_turmas_id')->unsigned();
            $table->foreign('disciplinas_turmas_id')
                ->references('id')
                ->on('disciplinas_turmas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->enum('dia', ['1','2','3','4','5','6','7']);
            $table->tinyInteger('horario')->unsigned();

            $table->timestamps();
            $table->unique(['disciplinas_turmas_id','dia', 'horario']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}
