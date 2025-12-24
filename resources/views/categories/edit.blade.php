@extends('layouts.app-sidebar')

@section('title', 'Edit Kategori: ' . $category->name)

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="period_type" class="form-label">Jenis Periode Siklus</label>
                <select name="period_type" id="period_type" class="form-select" required>
                    <option value="harian" {{ old('period_type', $category->period_type) == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="bulanan" {{ old('period_type', $category->period_type) == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahunan" {{ old('period_type', $category->period_type) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="parent_id" class="form-label">Induk Kategori (Opsional)</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    <option value="">-- Tidak Ada Induk --</option>
                    @foreach ($parentCategories as $parent)
                        @if($parent->id !== $category->id)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Update Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection