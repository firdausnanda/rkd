<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('m_matakuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matakuliah')->unique();
            $table->string('nama_matakuliah');
            $table->integer('sks');
            $table->integer('t');
            $table->integer('p');
            $table->integer('k');
            $table->string('kurikulum');
            $table->string('kode_prodi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
