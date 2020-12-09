<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipe', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idEquipe',4)->primary();
            $table->string('nomEquipe',40);
            $table->string('identiteResponsable',40)->nullable();
            $table->string('adressePostale',120)->nullable();
            $table->integer('nombrePersonnes');
            $table->string('nomPays',40);
            $table->char('hebergement',1);
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
        Schema::dropIfExists('equipe');
    }
}
