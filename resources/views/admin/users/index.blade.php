@extends('layouts.app-sidebar')

@section('title', 'Manajemen User')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Daftar User</h5>
    </div>
    <div class="card-body p-0">
        @if (session('success'))
            <div class="alert alert-success mx-3 mt-3">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 px-3">Nama</th>
                        <th class="py-3 px-3">Email</th>
                        <th class="py-3 px-3">Unit</th>
                        <th class="py-3 px-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="align-middle px-3">{{ $user->name }}</td>
                            <td class="align-middle px-3">{{ $user->email }}</td>
                            <td class="align-middle px-3">
                                <span class="badge bg-secondary">{{ $user->unit->name ?? 'BAU Admin' }}</span>
                            </td>
                            <td class="align-middle px-3">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-4">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection