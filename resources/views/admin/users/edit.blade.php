@extends('layouts.app-sidebar')

@section('title', 'Edit User: ' . $user->name)

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" value="{{ $user->name }}" disabled readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" class="form-control" value="{{ $user->email }}" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="unit_id" class="form-label">Unit</label>
                <select name="unit_id" id="unit_id" class="form-select">
                    <option value="" {{ is_null($user->unit_id) ? 'selected' : '' }}>-- BAU Admin (Tidak ada unit) --</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ $user->unit_id == $unit->id ? 'selected' : '' }}>
                            {{ $unit->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection