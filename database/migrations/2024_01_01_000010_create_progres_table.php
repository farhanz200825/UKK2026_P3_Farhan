<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('progres', function (Blueprint $table) {
            $table->id('id_progres');
            $table->foreignId('id_aspirasi')->constrained('aspirasi', 'id_aspirasi')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('keterangan_progres');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('progres');
    }
};