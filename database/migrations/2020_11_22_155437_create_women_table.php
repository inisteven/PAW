<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWomenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('women', function (Blueprint $table) {
            $table->bigIncrements('id_produkW');
            $table->string('nama_produkW');
            $table->double('harga_produkW');
            $table->string('deskripsi_produkW');
            $table->string('gambar_produkW');
            $table->integer('stok');
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
        Schema::dropIfExists('women');
    }
}
