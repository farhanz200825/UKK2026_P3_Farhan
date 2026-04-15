<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 20)->unique();
            $table->enum('tingkat', ['10', '11', '12']);
            $table->foreignId('id_jurusan')->constrained('jurusan', 'id_jurusan')->onDelete('cascade');
            $table->integer('kapasitas')->default(30);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
};