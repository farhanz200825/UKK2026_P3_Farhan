<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation
{
    private $successCount = 0;
    private $failures = [];

    public function model(array $row)
    {
        // Cari kelas berdasarkan nama kelas
        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();
        
        // Cari jurusan berdasarkan kode jurusan atau nama jurusan
        $jurusan = Jurusan::where('kode_jurusan', $row['jurusan'])
            ->orWhere('nama_jurusan', $row['jurusan'])
            ->first();

        if (!$kelas || !$jurusan) {
            $this->failures[] = [
                'row' => $row,
                'errors' => 'Kelas atau Jurusan tidak ditemukan'
            ];
            return null;
        }

        // Cek apakah user sudah ada
        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            $this->failures[] = [
                'row' => $row,
                'errors' => 'Email sudah terdaftar: ' . $row['email']
            ];
            return null;
        }

        // Cek apakah NIS sudah ada
        $existingSiswa = Siswa::where('nis', $row['nis'])->first();
        if ($existingSiswa) {
            $this->failures[] = [
                'row' => $row,
                'errors' => 'NIS sudah terdaftar: ' . $row['nis']
            ];
            return null;
        }

        // Format tanggal lahir dengan benar
        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            $tanggalLahir = $this->formatDate($row['tanggal_lahir']);
        }

        // Buat user
        $user = User::create([
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? 'siswa123'),
            'role' => 'siswa',
        ]);

        $this->successCount++;

        // Buat siswa
        return new Siswa([
            'user_id' => $user->id,
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'kelas' => $kelas->nama_kelas,
            'jurusan' => $jurusan->nama_jurusan,
            'id_kelas' => $kelas->id_kelas,
            'id_jurusan' => $jurusan->id_jurusan,
            'jenis_kelamin' => $row['jenis_kelamin'] ?? 'L',
            'tanggal_lahir' => $tanggalLahir,
            'alamat' => $row['alamat'] ?? null,
            'no_hp' => $row['no_hp'] ?? null,
        ]);
    }

    /**
     * Format tanggal ke format YYYY-MM-DD
     */
    private function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // Jika sudah dalam format YYYY-MM-DD
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                return $date;
            }
            
            // Coba parse dengan berbagai format
            $formats = [
                'm/d/Y',      // 5/15/2008
                'm/d/y',      // 5/15/08
                'd/m/Y',      // 15/5/2008
                'd/m/y',      // 15/5/08
                'Y-m-d',      // 2008-05-15
                'Y/m/d',      // 2008/05/15
                'd-m-Y',      // 15-05-2008
                'm-d-Y',      // 05-15-2008
            ];
            
            foreach ($formats as $format) {
                try {
                    $parsed = Carbon::createFromFormat($format, $date);
                    if ($parsed) {
                        return $parsed->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Jika semua gagal, coba gunakan strtotime
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return date('Y-m-d', $timestamp);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::warning('Gagal memformat tanggal: ' . $date . ' - ' . $e->getMessage());
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'email' => 'required|email',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'NIS wajib diisi',
            'nama.required' => 'Nama wajib diisi',
            'kelas.required' => 'Kelas wajib diisi',
            'jurusan.required' => 'Jurusan wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
        ];
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailures()
    {
        return $this->failures;
    }
}