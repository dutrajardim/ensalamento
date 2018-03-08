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
            $table->string('titulo', 255);
            $table->enum('status', ['A','T','E','P']);
            // A - Aguardando
            // T - Em trabalho
            // E - Erro
            // P - Pronto
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
        Schema::dropIfExists('horarios_salas');
        Schema::dropIfExists('ensalamentos');
    }
}
