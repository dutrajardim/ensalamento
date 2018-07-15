<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessoresFormacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professores_formacoes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('professores_id')->unsigned()->nullable();
            $table->foreign('professores_id')
                ->references('id')
                ->on('professores')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->integer('formacoes_id')->unsigned()->nullable();
            $table->foreign('formacoes_id')
                ->references('id')
                ->on('formacoes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            $table->smallInteger('ano')->unsigned();
            $table->enum('grau',[
                'Técnico',
                'Graduação',
                'Pos-graduação',
                'Mestrado',
                'Doutorado'
            ]);

            $table->unique(['professores_id', 'formacoes_id']);
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
        Schema::dropIfExists('professores_formacoes');
    }
}
