<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat password dengan Bcrypt
        $adminPassword = Hash::make('admin123');
        $guruPassword = Hash::make('guru123');
        $siswaPassword = Hash::make('siswa123');

        // ========== UPDATE ADMIN (id=1) ==========
        DB::table('users')->where('id', 1)->update([
            'email' => 'admin',
            'password' => $adminPassword,
            'role' => 'admin',
            'updated_at' => now(),
        ]);

        // ========== UPDATE GURU (id=2) ==========
        DB::table('users')->where('id', 2)->update([
            'email' => 'guru01',
            'password' => $guruPassword,
            'role' => 'guru',
            'updated_at' => now(),
        ]);

        // Update data guru
        DB::table('guru')->where('id', 1)->update([
            'user_id' => 2,
            'nip' => 'guru01',
            'nama' => 'Guru Demo',
            'mata_pelajaran' => 'Matematika',
            'jenis_kelamin' => 'L',
            'updated_at' => now(),
        ]);

        // ========== UPDATE SISWA (id=3) ==========
        DB::table('users')->where('id', 3)->update([
            'email' => '123456789',
            'password' => $siswaPassword,
            'role' => 'siswa',
            'updated_at' => now(),
        ]);

        // Update data siswa
        DB::table('siswa')->where('id', 1)->update([
            'user_id' => 3,
            'nis' => '123456789',
            'nama' => 'Siswa Demo',
            'kelas' => 'XII',
            'jurusan' => 'RPL',
            'jenis_kelamin' => 'L',
            'updated_at' => now(),
        ]);

        $this->command->info('==========================================');
        $this->command->info('Data users berhasil diupdate dengan Bcrypt!');
        $this->command->info('==========================================');
        $this->command->info('Akun Login (gunakan Bcrypt):');
        $this->command->info('→ Admin  | Email: admin     | Password: admin123');
        $this->command->info('→ Guru   | NIP: guru01     | Password: guru123');
        $this->command->info('→ Siswa  | NIS: 123456789  | Password: siswa123');
        $this->command->info('==========================================');
    }
}