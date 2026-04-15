<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->id('id_aspirasi');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kategori')->nullable()->constrained('kategori', 'id_kategori')->onDelete('set null');
            $table->foreignId('id_ruangan')->nullable()->constrained('ruangan', 'id_ruangan')->onDelete('set null');
            $table->string('lokasi', 100);
            $table->text('keterangan');
            $table->string('foto', 255)->nullable();
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aspirasi');
    }
};