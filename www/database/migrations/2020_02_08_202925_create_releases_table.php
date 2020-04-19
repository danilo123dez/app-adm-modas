<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->mediumInteger('boleta');
            $table->mediumInteger('romaneio');
            $table->string('cliente', 100);
            $table->bigInteger('loja_id')->references('id')->on('stores');
            $table->date('data_compra');
            $table->date('data_vencimento');
            $table->float('valor', 7, 2);
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
        Schema::dropIfExists('releases');
    }
}
