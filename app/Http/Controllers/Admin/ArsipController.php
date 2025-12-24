<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rak;
use App\Models\Skat;
use App\Models\Outner;
use App\Models\Surat;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    /**
     * Menampilkan halaman manajemen arsip (Rak, Skat, Outner)
     * dan daftar surat yang sudah diarsipkan, difilter berdasarkan tahun.
     *
     * @param Request $request Request HTTP, digunakan untuk mendapatkan query 'year'.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Ambil daftar tahun unik yang ada di tabel 'raks' untuk dropdown filter.
        $availableYears = Rak::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        // Tentukan tahun yang akan ditampilkan. Ambil dari URL (?year=xxxx), jika tidak ada, gunakan tahun saat ini.
        $selectedYear = $request->query('year', date('Y'));

        // Ambil data Rak untuk tahun yang dipilih, beserta relasi Skat dan Outner-nya (Eager Loading).
        $raks = Rak::with(['skats' => function ($query) {
            $query->orderBy('name', 'asc');
        }, 'skats.outners' => function ($query) {
            $query->orderBy('name', 'asc');
        }])
        ->where('year', $selectedYear) // Filter hanya rak tahun ini
        ->orderBy('name', 'asc')       // Urutkan rak berdasarkan nama
        ->get();

        // Query untuk mengambil surat yang sudah diarsipkan dan berada di Rak tahun yang dipilih.
        $suratsDiarsipkanQuery = Surat::with(['user', 'outner.skat.rak']) // Eager load relasi yang dibutuhkan
                                    ->where('status', 'Diarsipkan') // Hanya yang statusnya Diarsipkan
                                    ->whereHas('outner.skat.rak', function ($query) use ($selectedYear) {
                                        // Filter tambahan melalui relasi: pastikan Rak tempat surat diarsipkan adalah tahun yang dipilih.
                                        $query->where('year', $selectedYear);
                                    });
        // Lakukan paginasi pada query surat diarsipkan. withQueryString() agar filter tahun tetap terbawa saat pindah halaman.
        $suratsDiarsipkan = $suratsDiarsipkanQuery->latest()->paginate(10)->withQueryString();

        // Kirim semua data yang dibutuhkan ke view.
        return view('admin.arsip.index', compact('raks', 'suratsDiarsipkan', 'availableYears', 'selectedYear'));
    }
    
    /**
     * Menyimpan data Rak, Skat, atau Outner baru ke database.
     *
     * @param Request $request Request HTTP yang berisi data form.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Tentukan tipe data yang akan disimpan (rak, skat, atau outner) dari input form.
        $type = $request->input('type');
        
        if ($type == 'rak') { 
            $currentYear = date('Y'); // Dapatkan tahun saat ini.
            // Validasi: nama harus unik untuk tahun yang sama.
            $request->validate([
                'name' => 'required|string|unique:raks,name,NULL,id,year,' . $currentYear
            ]);
            // Buat Rak baru dengan nama dan tahun saat ini.
            Rak::create([
                'name' => $request->name,
                'year' => $currentYear
            ]);
        } elseif ($type == 'skat') { 
            // Validasi: rak_id harus ada dan nama harus diisi.
            $request->validate([
                'rak_id' => 'required|exists:raks,id', 
                'name' => 'required|string',
            ]);
            // Buat Skat baru.
            Skat::create($request->only('rak_id', 'name')); 
        } elseif ($type == 'outner') {
            // Validasi: skat_id harus ada dan nama harus diisi.
            $request->validate([
                'skat_id' => 'required|exists:skats,id', 
                'name' => 'required|string',
            ]);
            // Buat Outner baru.
            Outner::create($request->only('skat_id', 'name')); 
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', ucfirst($type) . ' berhasil ditambahkan.');
    }

    /**
     * Menghapus data Rak, Skat, atau Outner dari database.
     * Relasi onDelete('cascade') di migrasi akan otomatis menghapus data turunannya.
     *
     * @param string $type Tipe data ('rak', 'skat', 'outner').
     * @param int $id ID data yang akan dihapus.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($type, $id)
    {
        if ($type == 'rak') { 
            Rak::findOrFail($id)->delete(); // Cari atau gagal, lalu hapus.
        } elseif ($type == 'skat') { 
            Skat::findOrFail($id)->delete(); 
        } elseif ($type == 'outner') {
            Outner::findOrFail($id)->delete();
        }

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', ucfirst($type) . ' berhasil dihapus.');
    }

    /**
     * API Endpoint: Mengambil daftar Rak HANYA untuk tahun saat ini.
     * Digunakan untuk mengisi dropdown di modal arsip Dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRaks() 
    {
        // Ambil Rak yang kolom 'year'-nya sama dengan tahun sekarang.
        $raksForCurrentYear = Rak::where('year', date('Y'))->orderBy('name')->get();
        return response()->json($raksForCurrentYear);
    }

    /**
     * API Endpoint: Mengambil daftar Skat berdasarkan Rak yang dipilih.
     * Digunakan untuk mengisi dropdown di modal arsip Dashboard.
     *
     * @param Rak $rak Model Rak yang di-inject otomatis oleh Laravel (Route Model Binding).
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkatsByRak(Rak $rak) 
    {
        // Menggunakan relasi 'skats()' yang sudah didefinisikan di model Rak.
        return response()->json($rak->skats()->orderBy('name')->get()); 
    }

    /**
     * API Endpoint: Mengambil daftar Outner berdasarkan Skat yang dipilih.
     * Digunakan untuk mengisi dropdown di modal arsip Dashboard.
     *
     * @param Skat $skat Model Skat yang di-inject otomatis oleh Laravel.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOutnersBySkat(Skat $skat) 
    {
        // Menggunakan relasi 'outners()' yang sudah didefinisikan di model Skat.
        return response()->json($skat->outners()->orderBy('name')->get()); 
    }

    /**
     * Menyimpan data pengarsipan surat (menghubungkan Surat dengan Outner).
     *
     * @param Request $request Request HTTP berisi outner_id.
     * @param Surat $surat Model Surat yang akan diarsipkan (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeArsip(Request $request, Surat $surat)
    {
        // Validasi: pastikan outner_id yang dipilih ada di database.
        $request->validate([
            'outner_id' => 'required|exists:outners,id',
        ]);

        // Update data surat: set outner_id dan ubah status menjadi 'Diarsipkan'.
        $surat->update([
            'outner_id' => $request->outner_id,
            'status' => 'Diarsipkan',
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', 'Surat berhasil diarsipkan.');
    }

    /**
     * Memperbarui nama Rak, Skat, atau Outner.
     *
     * @param Request $request Request HTTP berisi nama baru.
     * @param string $type Tipe data ('rak', 'skat', 'outner').
     * @param int $id ID data yang akan diperbarui.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $type, $id)
    {
        // Validasi dasar: nama harus diisi.
        $request->validate(['name' => 'required|string|max:255']);

        // Cari item berdasarkan tipe dan ID.
        if ($type == 'rak') {
            $item = Rak::findOrFail($id);
            // Validasi unik: nama rak harus unik untuk tahun yang sama, KECUALI untuk ID rak ini sendiri.
            $request->validate(['name' => 'unique:raks,name,' . $id . ',id,year,' . $item->year]); 
        } elseif ($type == 'skat') {
            $item = Skat::findOrFail($id);
             // Validasi unik: nama skat harus unik dalam rak yang sama, KECUALI untuk ID skat ini sendiri.
             $request->validate(['name' => 'unique:skats,name,' . $id . ',id,rak_id,' . $item->rak_id]); 
        } elseif ($type == 'outner') {
            $item = Outner::findOrFail($id);
             // Validasi unik: nama outner harus unik dalam skat yang sama, KECUALI untuk ID outner ini sendiri.
             $request->validate(['name' => 'unique:outners,name,' . $id . ',id,skat_id,' . $item->skat_id]);
        } else {
            // Jika tipe tidak valid, kembali dengan error.
            return back()->withErrors('Tipe tidak valid.');
        }

        // Update nama item dan simpan.
        $item->name = $request->name;
        $item->save();

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', ucfirst($type) . ' berhasil diperbarui.');
    }

    /**
     * Membatalkan pengarsipan surat.
     * Mengembalikan status surat ke 'Disahkan' dan menghapus relasi ke Outner.
     *
     * @param Surat $surat Model Surat yang akan dibatalkan arsipnya (Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unarchive(Surat $surat)
    {
        // Pastikan surat ini memang sedang diarsipkan.
        if ($surat->status !== 'Diarsipkan') {
            return back()->withErrors('Surat ini tidak sedang dalam status diarsipkan.');
        }

        // Update: kembalikan status dan hapus outner_id.
        $surat->update([
            'status' => 'Disahkan', 
            'outner_id' => null,   
        ]);

        // Kembali ke halaman sebelumnya dengan pesan sukses.
        return back()->with('success', 'Pengarsipan surat berhasil dibatalkan.');
    }
}