<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->integer('codigoProducto');
            $table->boolean('estado');
            $table->biginteger('precioProducto');
            $table->biginteger('precioOriginal');
            $table->string('nombreProducto');
            $table->string('descripcionProducto')->nullable();
            $table->integer('numeroStock');

            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->foreign('id_categoria')->references('id')->on('categorias');
            $table->unsignedBigInteger('id_establecimiento')->nullable();
            $table->foreign('id_establecimiento')->references('id')->on('establecimientos');
            $table->unsignedBigInteger('id_subcategoria')->nullable();
            $table->foreign('id_subcategoria')->references('id')->on('sub_categorias');
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');

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
        Schema::dropIfExists('productos');
    }
}
