<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // TAMBAHKAN BARIS INI

class DocumentController extends Controller
{
    /**
     * Menyimpan dokumen baru ke database dan storage.
     */
    public function store(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:10240',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('documents', 'public');

            Document::create([
                'name' => $request->name,
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $originalName,
                'category_id' => $category->id,
                'user_id' => Auth::id(), // Baris ini sekarang akan berfungsi
            ]);
        }

        return back()->with('success', 'Dokumen berhasil di-upload!');
    }

    /**
     * Mengupdate data DAN file dokumen.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:10240',
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('document_file')) {
            Storage::disk('public')->delete($document->file_path);
            $file = $request->file('document_file');
            $originalName = $file->getClientOriginalName();
            $path = $file->store('documents', 'public');
            $dataToUpdate['file_path'] = $path;
            $dataToUpdate['file_name'] = $originalName;
        }

        $document->update($dataToUpdate);

        return back()->with('success', 'Data dokumen berhasil diperbarui!');
    }

    /**
     * Menghapus dokumen dari database dan storage.
     */
    public function destroy(Document $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return back()->with('success', 'Dokumen berhasil dihapus!');
    }

    /**
     * Mengirim file ke browser untuk di-download dengan nama aslinya.
     */
    public function download(Document $document)
    {
        $filePath = $document->file_path;
        $originalFileName = $document->file_name;
        return Storage::disk('public')->download($filePath, $originalFileName);
    }
}