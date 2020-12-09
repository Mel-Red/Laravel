<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etablissement', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idEtab',8)->primary();
            $table->string('nomEtab',45);
            $table->string('adresseRue',45);
            $table->string('codePostal',5);
            $table->string('ville',35);
            $table->string('tel',13);
            $table->string('adresseElectronique',70)->nullable();
            $table->tinyInteger('type');
            $table->string('civiliteResponsable',12);
            $table->string('nomResponsable',25);
            $table->string('prenomResponsable',25)->nullable();
            $table->integer('nombreChambresOffertes')->nullable();
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
        Schema::dropIfExists('etablissement');
    }
}
