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
        Schema::create('anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_induk', 20)->unique();
            $table->string('nomor_anggota', 20)->unique();
            $table->string('password');
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L','P']);
            $table->foreignId('kota_id')->nullable()->constrained('kotas')->onDelete('cascade');
            $table->date('tanggal_lahir')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas');
            $table->string('jabatan', 10);
            $table->string('alamat')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('anggotas');
    }
};
