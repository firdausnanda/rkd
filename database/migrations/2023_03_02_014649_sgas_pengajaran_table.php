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
        Schema::create('sgas_pengajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sgas')->constrained('sgas');
            $table->foreignId('id_matakuliah')->constrained('m_matakuliah');
            $table->foreignId('id_prodi')->constrained('m_prodi');
            $table->string('semester');
            $table->integer('kelas');
            $table->double('t_sks');
            $table->double('p_sks');
            $table->double('k_sks');
            $table->double('total_sks');
            $table->double('total');
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
