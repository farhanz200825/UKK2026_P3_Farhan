<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('history_status', function (Blueprint $table) {
            $table->id('id_history');
            $table->foreignId('id_aspirasi')->constrained('aspirasi', 'id_aspirasi')->onDelete('cascade');
            $table->enum('status_lama', ['Menunggu', 'Proses', 'Selesai']);
            $table->enum('status_baru', ['Menunggu', 'Proses', 'Selesai']);
            $table->foreignId('diubah_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('history_status');
    }
};