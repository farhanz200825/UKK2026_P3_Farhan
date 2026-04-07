<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat admin default
        User::create([
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Buat beberapa kategori aspirasi
        $kategoris = [
            'Sarana Belajar',
            'Fasilitas Umum',
            'Kebersihan',
            'Keamanan',
            'Lainnya'
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create([
                'nama_kategori' => $kategori,
            ]);
        }
    }
}