<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('kode_ruangan', 20)->unique();
            $table->string('nama_ruangan', 100);
            $table->enum('jenis_ruangan', ['Kelas', 'Laboratorium', 'Perpustakaan', 'Kantin', 'Kamar Mandi', 'Lapangan', 'Ruang Guru', 'Ruang Kepala Sekolah', 'Ruang UKS', 'Lainnya']);
            $table->string('lokasi', 100)->nullable();
            $table->integer('kapasitas')->nullable();
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'])->default('Baik');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruangan');
    }
};