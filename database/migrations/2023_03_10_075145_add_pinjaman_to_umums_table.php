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
        Schema::table('umums', function (Blueprint $table) {
            $table->unsignedInteger('batas_pengembalian')->default(7)->after('timezone');
            $table->unsignedInteger('batas_peminjaman')->default(0)->after('batas_pengembalian');
            $table->unsignedInteger('denda')->default(0)->after('batas_peminjaman');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('umums', function (Blueprint $table) {
            $table->dropColumn(['batas_pengembalian', 'batas_peminjaman', 'denda']);
        });
    }
};
