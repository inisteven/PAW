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
            $table->string('nama_productW');
            $table->double('harga_productW');
            $table->string('deskripsi_productW');
            $table->string('gambar_productW');
            $table->string('kategori');
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
