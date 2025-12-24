<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request): View
    {
        $searchTerm = $request->input('search');
        $categories = collect();
        $documents = collect();

        if ($searchTerm) {
            $allDocuments = Document::where('name', 'LIKE', "%{$searchTerm}%")
                                    ->with('category', 'user')
                                    ->latest()->get();

            $documents = $allDocuments->filter(function ($document) {
                return Gate::allows('view', $document->category);
            });
        } else {
            $allCategories = Category::whereNull('parent_id')
                                ->with('children.children.documents.user', 'documents.user')
                                ->latest()
                                ->get();

            // INI ADALAH BARIS YANG DIPERBAIKI
            $categories = $allCategories->filter(function ($category) {
                return Gate::allows('view', $category);
            });
        }

        return view('categories.index', compact('categories', 'documents', 'searchTerm'));
    }

    public function create(): View
    {
        // Izin: Hanya BAU yang bisa membuat kategori
        $this->authorize('view-admin-menu');
        $parentCategories = Category::all();
        return view('categories.create', compact('parentCategories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('view-admin-menu');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_type' => 'required|in:harian,bulanan,tahunan',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function edit(Category $category): View
    {
        $this->authorize('view-admin-menu');
        $parentCategories = Category::where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('view-admin-menu');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'period_type' => 'required|in:harian,bulanan,tahunan',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('view-admin-menu');
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}