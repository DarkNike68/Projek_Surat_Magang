@extends('layouts.app-sidebar')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="period_type" class="form-label">Jenis Periode Siklus</label>
                <select name="period_type" id="period_type" class="form-select" required>
                    <option value="" disabled selected>Pilih Periode</option>
                    <option value="harian" {{ old('period_type') == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ old('period_type') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ old('period_type') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="parent_id" class="form-label">Induk Kategori (Opsional)</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    <option value="">-- Tidak Ada Induk --</option>
                    @foreach ($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection