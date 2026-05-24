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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignUuid('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('file_path', 255)->nullable();
            $table->string('link', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();
            $table->enum('status', ['belum_mengumpulkan', 'sudah_mengumpulkan', 'dinilai'])->default('belum_mengumpulkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
