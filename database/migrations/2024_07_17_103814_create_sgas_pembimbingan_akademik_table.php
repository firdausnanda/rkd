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
        Schema::create('sgas_pembimbingan_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sgas')->constrained('sgas');
            $table->string('nama_mahasiswa');
            $table->string('nim');
            $table->string('semester');
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sgas_pembimbingan_akademik');
    }
};
