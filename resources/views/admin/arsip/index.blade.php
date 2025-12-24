@extends('layouts.app-sidebar')

@section('title', 'Manajemen Arsip')

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row">
    {{-- Rak --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rak</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.arsip.store') }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="type" value="rak">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Rak Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                <ul class="list-group">
                    @forelse ($raks as $rak)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $rak->name }} <span class="badge bg-secondary">{{ $rak->year }}</span></span>
                            <div>
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-update-url="{{ route('admin.arsip.update', ['type' => 'rak', 'id' => $rak->id]) }}"
                                        data-current-name="{{ $rak->name }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.arsip.destroy', ['type' => 'rak', 'id' => $rak->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus rak ini? Semua skat dan outner di dalamnya akan ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada rak.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Skat --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Skat</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.arsip.store') }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="type" value="skat">
                    <div class="mb-3">
                        <select name="rak_id" class="form-select" required>
                            <option value="">-- Pilih Rak --</option>
                            @foreach ($raks as $rak)
                                <option value="{{ $rak->id }}">{{ $rak->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Skat Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                @foreach ($raks as $rak)
                    <h6 class="mt-3">Di Rak: {{ $rak->name }} ({{$rak->year}})</h6>
                    <ul class="list-group">
                        @forelse ($rak->skats as $skat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                
                                <div>
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-update-url="{{ route('admin.arsip.update', ['type' => 'skat', 'id' => $skat->id]) }}"
                                            data-current-name="{{ $skat->name }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.arsip.destroy', ['type' => 'skat', 'id' => $skat->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus skat ini? Semua outner di dalamnya akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada skat.</li>
                        @endforelse
                    </ul>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Outner --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Outner</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.arsip.store') }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="type" value="outner">
                    <div class="mb-3">
                        <select name="skat_id" class="form-select" required>
                            <option value="">-- Pilih Skat --</option>
                            @foreach ($raks as $rak)
                                <optgroup label="Rak: {{ $rak->name }}">
                                    @foreach ($rak->skats as $skat)
                                        <option value="{{ $skat->id }}">{{ $skat->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Outner Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                 @foreach ($raks as $rak)
                    @foreach ($rak->skats as $skat)
                        <h6 class="mt-3">Di Skat: {{ $skat->name }} ({{ $rak->name }} - {{$rak->year}})</h6>
                        <ul class="list-group">
                            @forelse ($skat->outners as $outner)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $outner->name }}
                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-update-url="{{ route('admin.arsip.update', ['type' => 'outner', 'id' => $outner->id]) }}"
                                                data-current-name="{{ $outner->name }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.arsip.destroy', ['type' => 'outner', 'id' => $outner->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus outner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Belum ada outner.</li>
                            @endforelse
                        </ul>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Tabel Daftar Surat Terarsip --}}
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Daftar Surat Terarsip</h5>
        <form action="{{ route('admin.arsip.index') }}" method="GET" class="d-flex align-items-center">
            <label for="year_filter" class="form-label me-2 mb-0">Tahun:</label>
            <select name="year" id="year_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                @forelse($availableYears as $year)
                    <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @empty
                    <option>{{ date('Y') }}</option>
                @endforelse
            </select>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Pembuat</th>
                        <th class="text-center">Lokasi Arsip</th>
                        <th class="text-center">File</th>
                        <th class="text-center">Aksi</th> </tr>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratsDiarsipkan as $key => $surat)
                        <tr>
                            <td class="text-center">{{ $suratsDiarsipkan->firstItem() + $key }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->perihal }}</td>
                            <td>{{ $surat->user->full_name ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if ($surat->outner)
                                    <span class="badge bg-info">
                                        {{ $surat->outner->skat->rak->name }} ({{$surat->outner->skat->rak->year}}) / {{ $surat->outner->skat->name }} / {{ $surat->outner->name }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($surat->file_path)
                                    <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" class="btn btn-xs btn-success">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.surat.unarchive', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengarsipan surat ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-undo"></i> Batalkan Arsip
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada surat yang diarsipkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $suratsDiarsipkan->appends(['year' => $selectedYear])->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Nama</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editNameInput" class="form-label">Nama Baru</label>
                        <input type="text" class="form-control" id="editNameInput" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        // Tombol yang memicu modal
        const button = event.relatedTarget;

        // Ekstrak data dari atribut data-*
        const updateUrl = button.getAttribute('data-update-url');
        const currentName = button.getAttribute('data-current-name');

        // Dapatkan elemen form dan input di dalam modal
        const form = document.getElementById('editForm');
        const nameInput = document.getElementById('editNameInput');

        // Perbarui action form dan nilai input
        form.setAttribute('action', updateUrl);
        nameInput.value = currentName;
    });
});
</script>
@endpush