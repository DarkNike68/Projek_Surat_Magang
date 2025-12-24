<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterCode;
use Illuminate\Http\Request;

class LetterCodeController extends Controller
{
    /**
     * Menampilkan daftar Kode Surat (Jenis Surat atau Jabatan).
     * Menentukan tipe dan judul halaman berdasarkan URL yang diakses.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $type='';   // Tipe kode (jenis_surat atau jabatan)
        $title='';  // Judul halaman

        // Cek URL untuk menentukan tipe dan judul
        if ($request->is('admin/letter-codes/jenis-surat*')) {
            $type = 'jenis_surat';
            $title = 'Kode Jenis Surat';
        } elseif ($request->is('admin/letter-codes/jabatan*')) {
            $type = 'jabatan';
            $title = 'Kode Jabatan'; // Judul disesuaikan
        }

        // Ambil data LetterCode sesuai tipe yang ditentukan.
        // PENTING: Untuk 'jabatan', data ini mungkin tidak akan bisa diubah karena sudah di-seed.
        $letterCodes = LetterCode::where('type', $type)->get();
        
        // Kirim data ke view.
        return view('admin.letter-codes.index', compact('letterCodes', 'type', 'title'));
    }

    /**
     * Menyimpan Kode Surat baru (hanya untuk Jenis Surat, karena Jabatan sudah di-seed).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_codes', // Kode harus unik di seluruh tabel
            'type' => 'required|in:jenis_surat,jabatan', // Pastikan tipe valid
        ]);

        // Buat record baru.
        LetterCode::create($request->all());

        // Tentukan route redirect berdasarkan tipe yang baru saja ditambahkan.
        $redirectRoute = $request->type == 'jenis_surat' 
            ? 'admin.letter-codes.jenis-surat.index' 
            : 'admin.letter-codes.jabatan.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Kode surat berhasil ditambahkan.');
    }

    // Method show(), create(), edit() tidak digunakan dalam alur ini, bisa dibiarkan kosong atau dihapus.

    /**
     * Memperbarui data Kode Surat (hanya untuk Jenis Surat).
     *
     * @param Request $request
     * @param LetterCode $letterCode Model LetterCode yang akan diupdate (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, LetterCode $letterCode)
    {
        // Pengecekan PENTING: Jika user mencoba mengubah KODE (bukan hanya nama)
        // DAN kode tersebut sudah pernah digunakan di tabel 'surats' (baik sebagai jabatan atau jenis),
        // maka jangan izinkan perubahan kode, hanya boleh ubah nama.
        if ($request->code != $letterCode->code && ($letterCode->suratAsJabatan()->exists() || $letterCode->suratAsJenis()->exists())) {
            return back()->with('error', 'Peringatan! Kode ini tidak dapat diubah karena sudah digunakan di beberapa surat. Anda hanya bisa mengubah nama.');
        }

        // Validasi input (nama dan kode). Kode harus unik, kecuali untuk ID ini sendiri.
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_codes,code,' . $letterCode->id,
        ]);

        // Lakukan update hanya pada nama dan kode. Tipe tidak boleh diubah.
        $letterCode->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        
        // Tentukan route redirect berdasarkan tipe data yang diupdate.
        $redirectRoute = $letterCode->type == 'jenis_surat' // Gunakan $letterCode->type
            ? 'admin.letter-codes.jenis-surat.index' 
            : 'admin.letter-codes.jabatan.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Kode surat berhasil diperbarui.');
    }

    /**
     * Menghapus data Kode Surat (hanya untuk Jenis Surat).
     * Mencegah penghapusan jika kode sudah digunakan.
     *
     * @param LetterCode $letterCode Model LetterCode yang akan dihapus (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LetterCode $letterCode)
    {
        // Pengecekan PENTING: Hitung apakah ada surat yang menggunakan kode ini (sebagai jabatan atau jenis).
        if($letterCode->suratAsJabatan()->count() > 0 || $letterCode->suratAsJenis()->count() > 0){
            // Jika ada (count > 0), jangan izinkan hapus dan beri pesan error.
            return back()->with('error', 'Peringatan! Kode ini tidak dapat dihapus karena sudah digunakan di beberapa surat.');
        }
        
        // Jika tidak ada surat yang menggunakan, baru boleh dihapus.
        $letterCode->delete();

        // Tentukan route redirect berdasarkan tipe data yang dihapus.
        $redirectRoute = $letterCode->type == 'jenis_surat' 
            ? 'admin.letter-codes.jenis-surat.index' 
            : 'admin.letter-codes.jabatan.index';

        return redirect()->route($redirectRoute)
                         ->with('success', 'Kode surat berhasil dihapus.');
    }

    /**
     * (Kemungkinan tidak terpakai?) Menampilkan direktori kode jabatan.
     * Jika tidak dipakai, method ini bisa dihapus.
     *
     * @return \Illuminate\View\View
     */
    public function showJabatanDirectory()
    {
        $jabatanCodes = LetterCode::where('type', 'jabatan')->orderBy('name')->get();
        return view('letter-codes.directory', compact('jabatanCodes'));
    }
}