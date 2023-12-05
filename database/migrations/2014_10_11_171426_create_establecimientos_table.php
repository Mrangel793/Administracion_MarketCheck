<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablecimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('Nit');
            $table->boolean('Estado');
            $table->string('NombreEstablecimiento');
            $table->string('DireccionEstablecimiento');
            $table->string('CorreoEstablecimiento');
            $table->text('Lema')->nullable();
            $table->string('ColorInterfaz')->nullable();
            $table->string('Imagen')->nullable();
            $table->string('Logo');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('establecimientos');
    }
}
