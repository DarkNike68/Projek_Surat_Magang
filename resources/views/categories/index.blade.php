@extends('layouts.app-sidebar')

@section('title','Daftar Kategori dan Dokumen')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Tombol Tambah Kategori hanya untuk BAU Admin --}}
            @can('view-admin-menu')
                <a href="{{ route('admin.categories.create') }}" class="btn btn-dark mb-4">
                    Tambah Kategori Baru
                </a>
            @endcan

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="accordion" id="categoryAccordion">
                @forelse ($categories as $category)
                    <x-category-item :category="$category" />
                @empty
                    <div class="alert alert-warning text-center" role="alert">
                        Tidak ada kategori yang bisa Anda akses.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- MODAL TETAP DI SINI --}}
    @include('categories.partials.modals')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadModal = document.getElementById('uploadModal');
    if (uploadModal) {
        uploadModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const categoryId = button.getAttribute('data-category-id');
        const form = document.getElementById('uploadForm');
        // BARIS INI SUDAH DIPERBAIKI
        form.action = `/admin/categories/${categoryId}/documents`;
    });
    }
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const documentId = button.getAttribute('data-document-id');
            const documentName = button.getAttribute('data-document-name');
            const documentDesc = button.getAttribute('data-document-description');
            const form = document.getElementById('editForm');
            const inputName = document.getElementById('edit_name');
            const inputDesc = document.getElementById('edit_description');
            form.action = `/documents/${documentId}`;
            inputName.value = documentName;
            inputDesc.value = documentDesc;
        });
    }
});
</script>
@endpush