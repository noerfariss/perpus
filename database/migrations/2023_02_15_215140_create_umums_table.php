<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('umums', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('alamat')->nullable();
            $table->foreignId('provinsi_id')->nullable()->constrained('provinsis')->onDelete('cascade');
            $table->foreignId('kota_id')->nullable()->constrained('kotas')->onDelete('cascade');
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->onDelete('cascade');
            $table->string('telpon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->enum('timezone', ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'])->nullable();
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
        Schema::dropIfExists('umums');
    }
};
