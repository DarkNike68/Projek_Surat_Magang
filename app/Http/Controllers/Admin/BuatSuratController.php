<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterCode;
use App\Models\Surat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BuatSuratController extends Controller
{
    /**
     * Menampilkan langkah pertama: memilih Jabatan Tujuan.
     * Mengosongkan session 'surat_data' untuk memulai proses baru.
     *
     * @return \Illuminate\View\View
     */
    public function showJabatanStep()
    {
        session()->forget('surat_data'); // Hapus data sesi lama
        // Ambil semua LetterCode bertipe 'jabatan', urutkan berdasarkan ID (urutan pembuatan)
        $jabatan = LetterCode::where('type','jabatan')->orderBy('id', 'asc')->get();
        return view('admin.surat.step1_jabatan', compact('jabatan'));
    }

    /**
     * Menyimpan pilihan Jabatan Tujuan ke dalam session.
     *
     * @param Request $request Request HTTP berisi letter_code_jabatan_id.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeJabatanStep(Request $request)
    {
        // Validasi: pastikan ID jabatan yang dikirim ada di database.
        $validated = $request->validate([
            'letter_code_jabatan_id' => 'required|exists:letter_codes,id',
        ]);

        // Inisialisasi session jika belum ada.
        if (empty(session('surat_data'))) {
            session(['surat_data' => []]);
        }

        // Simpan ID jabatan ke session.
        session()->put('surat_data.jabatan_id', $validated['letter_code_jabatan_id']);

        // Arahkan ke langkah berikutnya.
        return redirect()->route('surat.step2.show');
    }

    /**
     * Menampilkan langkah kedua: memilih Jenis Surat.
     * Memastikan langkah pertama (memilih jabatan) sudah dilakukan.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showJenisSuratStep()
    {
        // Ambil ID jabatan dari session.
        $jabatanId = session('surat_data.jabatan_id');
        
        // Jika ID jabatan tidak ada (misal sesi habis atau akses langsung), kembali ke langkah 1.
        if (empty($jabatanId)){
            return redirect()->route('surat.step1.show');
        }

        // Ambil detail jabatan yang dipilih untuk ditampilkan di view.
        $jabatanTerpilih = LetterCode::find($jabatanId);
        // Ambil semua LetterCode bertipe 'jenis_surat', urutkan berdasarkan ID.
        $jenisSurat = LetterCode::where('type', 'jenis_surat')->orderBy('id', 'asc')->get();

        return view('admin.surat.step2_jenissurat', compact('jenisSurat','jabatanTerpilih'));
    }

    /**
     * Menyimpan pilihan Jenis Surat ke dalam session.
     *
     * @param Request $request Request HTTP berisi letter_code_jenis_surat_id.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeJenisSuratStep(Request $request)
    {
        // Validasi: pastikan ID jenis surat yang dikirim ada.
        $validated = $request->validate([
            'letter_code_jenis_surat_id' => 'required|exists:letter_codes,id',
        ]);

        // Pastikan data jabatan masih ada di session.
        if (empty(session('surat_data.jabatan_id'))) {
            return redirect()->route('surat.step1.show')->withErrors('Sesi telah berakhir, silahkan mulai lagi');
        }

        // Simpan ID jenis surat ke session.
        session()->put('surat_data.jenis_id', $validated['letter_code_jenis_surat_id']);

        // Arahkan ke langkah terakhir.
        return redirect()->route('surat.step3.show');
    }

    /**
     * Menampilkan langkah ketiga: konfirmasi akhir dan input perihal.
     * Menampilkan riwayat 5 surat terakhir dengan kombinasi jabatan & jenis yang sama.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showFinalStep()
    {
        // Ambil ID jabatan dan jenis surat dari session.
        $jabatanId = session('surat_data.jabatan_id');
        $jenisId = session('surat_data.jenis_id');

        // Jika salah satu ID tidak ada, kembali ke langkah 1.
        if (!$jabatanId || !$jenisId){
            return redirect()->route('surat.step1.show')->withErrors('Sesi telah berakhir, silahkan mulai lagi');
        }

        // Ambil detail jabatan dan jenis surat terpilih.
        $jabatanTerpilih = LetterCode::find($jabatanId);
        $jenisSuratTerpilih = LetterCode::find($jenisId);

        // Ambil 5 surat terakhir yang dibuat dengan kombinasi jabatan dan jenis yang sama.
        $historySurat = Surat::where('letter_code_jabatan_id', $jabatanId)
                                ->where('letter_code_jenis_surat_id', $jenisId)
                                ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
                                ->take(5) // Ambil hanya 5
                                ->get();

        return view('admin.surat.step3_final', compact(
            'jabatanTerpilih', 
            'jenisSuratTerpilih',
            'historySurat' // Kirim riwayat ke view
        ));
    }

    /**
     * Menghasilkan nomor surat baru berdasarkan pilihan, input perihal,
     * dan menyimpannya ke database.
     *
     * @param Request $request Request HTTP berisi 'perihal'.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\RedirectResponse
     */
    public function generateAndStore(Request $request)
    {
        // Validasi input perihal.
        $validated = $request->validate([
            'perihal' => 'required|string|max:255',
        ]);

        try {
            // Gunakan transaction untuk memastikan semua query berhasil atau gagal bersamaan.
            $surat = DB::transaction(function () use ($request) {
                // Ambil ID dari session.
                $jabatanId = session('surat_data.jabatan_id');
                $jenisId = session('surat_data.jenis_id');

                if (!$jabatanId || !$jenisId){
                    throw new \Exception('Sesi telah berakhir, silakan mulai lagi dari Langkah 1.');
                }

                // Ambil kode dari database berdasarkan ID. findOrFail akan error jika ID tidak ditemukan.
                $kodeJabatan = LetterCode::findOrFail($jabatanId)->code;
                $kodeJenisSurat = LetterCode::findOrFail($jenisId)->code;
                
                // Gunakan waktu saat ini untuk perhitungan.
                $now = Carbon::now();
                $bulanRomawi = $this->convertToRoman($now->month);
                $tahun = $now->year;
                $namaInstitusi = 'UAM'; // Bisa diambil dari config jika diperlukan

                // Hitung nomor urut berikutnya untuk kombinasi jabatan, jenis, dan tahun ini.
                // lockForUpdate() mencegah kondisi race condition jika ada 2 user membuat nomor bersamaan.
                $nomorUrut = Surat::where('letter_code_jabatan_id', $jabatanId)
                                    ->where('letter_code_jenis_surat_id', $jenisId)
                                    ->where('tahun', $tahun)
                                    ->lockForUpdate() // Kunci baris yang relevan selama transaksi
                                    ->count() + 1; // Hitung jumlah yang sudah ada, tambah 1

                // Format nomor surat lengkap menggunakan sprintf. %03d untuk 3 digit nomor urut (001, 002, dst).
                $nomorSuratLengkap = sprintf(
                    "%03d/%s/%s/%s/%s/%d",
                    $nomorUrut, $kodeJenisSurat, $namaInstitusi, $kodeJabatan, $bulanRomawi, $tahun
                );

                // Dapatkan user yang sedang login.
                $currentUser = Auth::user();

                // Buat record baru di tabel 'surats'.
                return Surat::create([
                    'nomor_surat' => $nomorSuratLengkap,
                    'nomor_urut_per_tahun' => $nomorUrut,
                    'tahun' => $tahun,
                    'bulan_romawi' => $bulanRomawi,
                    'letter_code_jabatan_id' => $jabatanId,
                    'letter_code_jenis_surat_id' => $jenisId,
                    'perihal' => $request['perihal'],
                    'user_id' => $currentUser->id_users, // Pastikan kolom ID user benar
                    'status' => 'Belum upload', // Status awal saat nomor dibuat
                ]);
            }); // Akhir transaction

            // Hapus data dari session setelah berhasil.
            session()->forget('surat_data');
            // Redirect ke dashboard dengan pesan sukses.
            return redirect()->route('dashboard')->with('success', "Nomor surat berhasil dibuat: " . $surat->nomor_surat);

        } catch (\Exception $e) {
            // Jika terjadi error (misal session habis, query gagal), kembali ke halaman sebelumnya dengan pesan error.
            return back()->withErrors('Gagal membuat surat: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * API Endpoint: Menghasilkan preview nomor surat berikutnya
     * tanpa menyimpannya ke database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function previewNomorSurat()
    {
        // Gunakan transaction meskipun hanya membaca, untuk konsistensi.
        return DB::transaction(function(){
            $jabatanId = session('surat_data.jabatan_id');
            $jenisId = session('surat_data.jenis_id');

            if (!$jabatanId || !$jenisId){
                return response()->json(['error' => 'Sesi tidak valid, silakan mulai dari awal.'], 422); // Kirim error 422
            }

            // Logika perhitungan nomor urut dan format sama seperti generateAndStore,
            // TAPI tanpa lockForUpdate() dan Surat::create().
            $kodeJabatan = LetterCode::find($jabatanId)->code;
            $kodeJenisSurat = LetterCode::find($jenisId)->code;
            $tahun = Carbon::now()->year;
            $bulanRomawi = $this->convertToRoman(Carbon::now()->month);
            $namaInstitusi = 'UAM';
            $nomorUrut = Surat::where('letter_code_jabatan_id', $jabatanId)
                                ->where('letter_code_jenis_surat_id', $jenisId)
                                ->where('tahun', $tahun)
                                ->count() + 1; // Cukup hitung, tidak perlu lock

            // Format nomor surat (contoh format berbeda, sesuaikan jika perlu)
            $nomorSuratLengkap = sprintf(
                "%03d/%s/%s/%s/%s/%d", // Gunakan format yang sama dengan generateAndStore
                $nomorUrut, $kodeJenisSurat, $namaInstitusi, $kodeJabatan, $bulanRomawi, $tahun
            );

            // Kembalikan nomor surat dalam format JSON.
            return response()->json(['nomor_surat' => $nomorSuratLengkap]);
        });
    }

    /**
     * Mengunduh file template .docx berdasarkan jenis surat yang dipilih.
     * Nama file template diambil dari kolom 'template_file' di database.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadTemplate()
    {
        // Ambil ID jenis surat dari session.
        $jenisId = session('surat_data.jenis_id');

        if (!$jenisId) {
            return back()->withErrors('Sesi tidak valid atau jenis surat belum dipilih.');
        }

        // Cari data jenis surat di database.
        $jenisSurat = LetterCode::findOrFail($jenisId);

        // Cek apakah ada nama file template yang tersimpan.
        if (empty($jenisSurat->template_file)) {
            return back()->withErrors('Template untuk jenis surat ini tidak tersedia.');
        }

        $pathToFile = $jenisSurat->template_file; // Contoh: 'templates/surat-keputusan.docx'

        // Cek apakah file fisik benar-benar ada di storage/app/public/.
        if (!Storage::disk('public')->exists($pathToFile)) {
            return back()->withErrors('File template tidak ditemukan di server. Pastikan nama file di seeder sudah benar dan file sudah ada di folder storage/app/public/templates.');
        }

        // Buat nama file yang akan tampil di dialog download pengguna (lebih rapi).
        $downloadName = $jenisSurat->name . '.docx'; // Contoh: "Surat Keputusan.docx"

        // Siapkan header HTTP untuk mencegah browser menyimpan cache file download.
        $headers = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        // Mulai proses download menggunakan Storage facade.
        return Storage::disk('public')->download($pathToFile, $downloadName, $headers);
    }

    /**
     * Fungsi helper privat untuk mengubah angka bulan menjadi angka Romawi.
     *
     * @param int $number Angka bulan (1-12).
     * @return string Angka Romawi (I-XII).
     */
    private function convertToRoman($number)
    {
        // Peta nilai angka Romawi
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    } 
}