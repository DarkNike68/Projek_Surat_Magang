<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class RiwayatSuratController extends Controller
{
    /**
     * Menampilkan halaman riwayat surat.
     * Memfilter data berdasarkan tahun yang dipilih dan hak akses pengguna.
     *
     * @param Request $request Request HTTP untuk mendapatkan query 'year'.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 1. Ambil semua tahun unik dari kolom 'created_at' di tabel 'surats'.
        //    Ini digunakan untuk mengisi dropdown filter tahun.
        $availableYears = Surat::select(DB::raw('YEAR(created_at) as year'))
                                ->distinct()         // Hanya ambil nilai unik
                                ->orderBy('year', 'desc') // Urutkan tahun terbaru di atas
                                ->pluck('year');     // Ambil hanya kolom 'year'

        // 2. Tentukan tahun yang akan ditampilkan. Ambil dari URL (?year=xxxx), 
        //    jika tidak ada, gunakan tahun saat ini sebagai default.
        $selectedYear = $request->query('year', date('Y'));

        // Dapatkan user yang sedang login.
        $user = Auth::user();
        // Mulai query Surat, eager load relasi user, jabatan, dan jenisSurat.
        $query = Surat::with(['user', 'jabatan', 'jenisSurat']);

        // 3. Filter berdasarkan hak akses.
        //    Jika user bukan admin, hanya tampilkan surat miliknya.
        if (Gate::denies('view-admin-menu')) {
            $query->where('user_id', $user->id_users);
        }
        
        // 4. Filter utama: hanya tampilkan surat yang 'created_at'-nya cocok dengan tahun yang dipilih.
        $query->whereYear('created_at', $selectedYear);

        // 5. Ambil hasil query, urutkan dari yang terbaru, dan lakukan paginasi (15 per halaman).
        $surats = $query->latest()->paginate(15);

        // 6. Kirim data ke view.
        return view('admin.riwayat.index', compact('surats', 'availableYears', 'selectedYear'));
    }
}