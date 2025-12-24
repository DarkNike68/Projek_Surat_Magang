<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Gate;
// use function Pest\Laravel\get; // Ini sepertinya tidak terpakai, bisa dihapus jika benar

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     * Menampilkan daftar surat sesuai hak akses (admin atau pengguna biasa).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user(); // Dapatkan user yang sedang login
        $query = Surat::with('user'); // Mulai query surat, eager load relasi 'user'

        // Cek hak akses menggunakan Gate 'view-admin-menu'.
        // Jika user TIDAK BISA melihat menu admin (artinya pengguna biasa)...
        if (Gate::denies('view-admin-menu')) {
            // ...maka filter surat agar hanya menampilkan surat milik user tersebut.
            $query->where('user_id', $user->id_users); 
        }
        // Jika user BISA (admin), query tidak difilter, semua surat akan diambil.

        // Ambil hasil query, urutkan dari yang terbaru, dan lakukan paginasi (10 per halaman).
        $surats = $query->latest()->paginate(10);

        // Kirim data surat ke view 'dashboard'.
        return view('dashboard', compact('surats'));
    }

    /**
     * Menangani proses upload file (draf atau revisi) oleh pengguna.
     *
     * @param Request $request Request HTTP berisi file.
     * @param Surat $surat Model Surat yang akan diupload filenya (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFile(Request $request, Surat $surat)
    {
        // Otorisasi: Pastikan user yang upload adalah pemilik surat ATAU admin.
        if ($surat->user_id != Auth::user()->id_users && Gate::denies('view-admin-menu')) {
            abort(403, 'AKSI TIDAK DIIZINKAN.'); // Hentikan proses jika tidak berhak.
        }

        // Validasi: file harus ada, berupa PDF, dan maksimal 2MB.
        $request->validate([
            'file_surat' => 'required|file|mimes:pdf|max:2048' // Hanya izinkan PDF
        ]);

        // Simpan file ke storage/app/public/surat-files. 
        // Nama file akan di-generate otomatis oleh Laravel untuk menghindari konflik.
        $path = $request->file('file_surat')->store('surat-files', 'public');

        // Update record surat: simpan path file dan ubah status menjadi 'Menunggu Persetujuan'.
        $surat->update([
            'file_path' => $path,
            'status' => 'Menunggu Persetujuan',
        ]);

        // Kembali ke dashboard dengan pesan sukses.
        return back()->with('success', 'File draf berhasil diunggah. Menunggu review dari Admin.');
    }

    /**
     * Menangani proses update status dan catatan oleh Admin.
     *
     * @param Request $request Request HTTP berisi status baru dan catatan.
     * @param Surat $surat Model Surat yang akan diupdate (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminUpdate(Request $request, Surat $surat)
    {
        // Validasi: status harus 'Disahkan' atau 'Revisi', catatan boleh kosong.
        $request->validate([
            'catatan' => 'nullable|string',
            'status' => 'required|in:Disahkan,Revisi',
        ]);

        // Update record surat dengan status dan catatan baru dari form.
        $surat->update([
            'catatan' => $request->catatan,
            'status' => $request->status,
        ]);

        // Kembali ke dashboard dengan pesan sukses.
        return back()->with('success', 'Status surat berhasil diperbarui.');
    }
}