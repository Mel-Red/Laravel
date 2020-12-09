<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribution', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idEtab',8);
            $table->char('idEquipe',4);
            $table->integer('nombreChambres');
            $table->timestamps();
            $table->primary(['idEtab','idEquipe']);
        });

        Schema::table('attribution', function (Blueprint $table) {
            $table->foreign('idEtab')
                ->references('idEtab')
                ->on('etablissement')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('idEquipe')
                ->references('idEquipe')
                ->on('equipe')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('attribution');
    }
}
