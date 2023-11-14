<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('compra_id');
            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->string('nombre');
            $table->integer('cantidad');
            $table->decimal('precio');
            $table->decimal('total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras_productos');
    }
}
