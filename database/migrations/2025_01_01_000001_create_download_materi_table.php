<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('download_materi')) {
            Schema::create('download_materi', function (Blueprint $table) {
                $table->id();
                $table->string('keterangan');
                $table->string('file_path');
                $table->string('kategori')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('download_materi');
    }
};
