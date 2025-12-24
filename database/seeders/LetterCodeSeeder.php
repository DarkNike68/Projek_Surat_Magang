<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LetterCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 1. Nonaktifkan pengecekan foreign key untuk sementara
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel untuk memastikan data selalu bersih
        DB::table('letter_codes')->truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // Siapkan timestamp untuk semua data
        $now = Carbon::now();

        // 4. Siapkan semua data dalam bentuk array
        $codes = [
            // --- DATA JENIS SURAT ---
            ['name' => 'Surat Keputusan', 'code' => 'SK', 'template_file' => 'templates/'.Str::slug('Surat Keputusan').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Peraturan', 'code' => 'P', 'template_file' => 'templates/'.Str::slug('Peraturan').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nota Kesepahaman', 'code' => 'MOU', 'template_file' => 'templates/'.Str::slug('Nota Kesepahaman').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Perjanjian', 'code' => 'MOA', 'template_file' => 'templates/'.Str::slug('Surat Perjanjian').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rencana Implementasi Kerjasama', 'code' => 'IA', 'template_file' => 'templates/'.Str::slug('Rencana Implementasi Kerjasama').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Keterangan/Pernyataan', 'code' => 'SKP', 'template_file' => 'templates/'.Str::slug('Surat Keterangan Pernyataan').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Tugas', 'code' => 'ST', 'template_file' => 'templates/'.Str::slug('Surat Tugas').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Perintah Perjalanan Dinas', 'code' => 'SPPD', 'template_file' => 'templates/'.Str::slug('Surat Perintah Perjalanan Dinas').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Kuasa', 'code' => 'S.KUASA', 'template_file' => 'templates/'.Str::slug('Surat Kuasa').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Edaran', 'code' => 'SE', 'template_file' => 'templates/'.Str::slug('Surat Edaran').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Memo Internal', 'code' => 'MI', 'template_file' => 'templates/'.Str::slug('Memo Internal').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Berita Acara', 'code' => 'BA', 'template_file' => 'templates/'.Str::slug('Berita Acara').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Rekomendasi', 'code' => 'SRM', 'template_file' => 'templates/'.Str::slug('Surat Rekomendasi').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Pengantar', 'code' => 'SP', 'template_file' => 'templates/'.Str::slug('Surat Pengantar').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Perintah', 'code' => 'SPT', 'template_file' => 'templates/'.Str::slug('Surat Perintah').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Surat Undangan', 'code' => 'UNd', 'template_file' => 'templates/'.Str::slug('Surat Undangan').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sertifikat', 'code' => 'SERT', 'template_file' => 'templates/'.Str::slug('Sertifikat').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Nota Dinas', 'code' => 'ND', 'template_file' => 'templates/'.Str::slug('Nota Dinas').'.docx', 'type' => 'jenis_surat', 'created_at' => $now, 'updated_at' => $now],
            
            // --- DATA JABATAN ---
            ['name' => 'Senat', 'code' => 'A', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dewan Pertimbangan/Pengawas', 'code' => 'B', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Rektor', 'code' => 'RK', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wakil Rektor I', 'code' => 'WR.I', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wakil Rektor II', 'code' => 'WR.II', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fakultas Ilmu Kesehatan', 'code' => 'FIK', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fakultas Sains dan Teknologi', 'code' => 'FST', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Fakultas Ekonomi dan Bisnis', 'code' => 'FEB', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LPPM', 'code' => 'LPPM', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LP3M', 'code' => 'LP3M', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'LPTIK', 'code' => 'LPTIK', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'UPT Perpustakaan', 'code' => 'LIB', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biro Keuangan', 'code' => 'KEU', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biro Kepegawaian', 'code' => 'SDM', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biro Administrasi Akademik', 'code' => 'BAA', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biro Administrasi Umum', 'code' => 'BAU', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Biro Sarana & Prasarana', 'code' => 'BSP', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Revenue Genereration Unit', 'code' => 'RGU', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kemahasiswaan & Prestasi', 'code' => 'KMH', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Alumni & Kerjasama', 'code' => 'AKS', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hubungan Masyarakat & Marketing', 'code' => 'HMR', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi D3 Teknologi Laboratorium Medis', 'code' => 'KP.A', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Farmasi', 'code' => 'KP.B', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi D3 Farmasi', 'code' => 'KP.C', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Administrasi Kesehatan', 'code' => 'KP.D', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Fisioterapi', 'code' => 'KP.E', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Bisnis Digital', 'code' => 'KP.F', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Kewirasahaan', 'code' => 'KP.G', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Rekayasa Perangkat Lunak', 'code' => 'KP.H', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi S1 Sistem Informasi', 'code' => 'KP.I', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Program Studi Pendidikan Profesi Apoteker', 'code' => 'KP.J', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Laboratorium Pendidikan', 'code' => 'LAB', 'template_file' => null, 'type' => 'jabatan', 'created_at' => $now, 'updated_at' => $now],
        ];

        // 5. Masukkan semua data sekaligus ke dalam database
        DB::table('letter_codes')->insert($codes);
    }
}