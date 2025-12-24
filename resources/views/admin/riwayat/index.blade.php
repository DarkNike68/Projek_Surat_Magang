@extends('layouts.app-sidebar')

@section('title', 'Riwayat Surat')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Seluruh Riwayat Pembuatan Surat</h5>
        
        <form action="{{ route('riwayat.index') }}" method="GET" class="d-flex align-items-center">
            <label for="year_filter" class="form-label me-2 mb-0">Tahun:</label>
            <select name="year" id="year_filter" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
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
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Pembuat</th>
                        <th>Jabatan Tujuan</th>
                        <th>Jenis Surat</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surats as $key => $surat)
                        <tr>
                            <td>{{ $surats->firstItem() + $key }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->perihal }}</td>
                            <td>{{ $surat->user->full_name ?? 'N/A' }}</td>
                            <td>{{ $surat->jabatan->name ?? 'N/A' }}</td>
                            <td>{{ $surat->jenisSurat->name ?? 'N/A' }}</td>
                            <td>{{ $surat->created_at->translatedFormat('d F Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data surat yang dibuat untuk tahun {{ $selectedYear }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $surats->appends(['year' => $selectedYear])->links() }}
        </div>
    </div>
</div>

@endsection