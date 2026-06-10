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
        Schema::table('tugas', function (Blueprint $table) {
            $table->foreignUuid('guru_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->foreignUuid('matapelajaran_id')->nullable()->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('tipe', 50)->default('Homework');
            $table->integer('max_score')->default(100);
            $table->integer('kkm')->default(75);
            $table->string('status', 20)->default('PUBLISHED'); // DRAFT, PUBLISHED, COMPLETED
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropForeign(['guru_id']);
            $table->dropForeign(['matapelajaran_id']);
            $table->dropColumn(['guru_id', 'matapelajaran_id', 'tipe', 'max_score', 'kkm', 'status']);
        });
    }
};
