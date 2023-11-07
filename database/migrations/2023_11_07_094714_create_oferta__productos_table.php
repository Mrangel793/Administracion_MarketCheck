<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertaProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oferta__productos', function (Blueprint $table) {
            $table->unsignedBigInteger("id_producto");
            $table->foreign("id_producto")->references("id")->on("productos");
            $table->unsignedBigInteger("id_oferta");
            $table->foreign("id_oferta")->references("id")->on("ofertas");
            $table->double("porcentaje");
            $table->double("precio_oferta");


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
        Schema::dropIfExists('oferta__productos');
    }
}
