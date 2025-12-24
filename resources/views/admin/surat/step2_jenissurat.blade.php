@push('styles')
<style>
    .btn-card {
        border: 1px solid #dee2e6;
        background-color: white;
        color: #212529;
        text-align: center;
        transition: all 0.2s ease-in-out;
    }
    .btn-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,.1);
        border-color: #0d6efd;
    }
</style>
@endpush

@extends('layouts.app-sidebar')

@section('title', 'Pilih Jenis Surat')

@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Langkah 2: Pilih Jenis Surat</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-baseline mb-4">
            <p class="text-muted me-2 mb-0">Ditujukan Kepada:</p>
            <h4 class="fw-bold mb-0">{{ $jabatanTerpilih->name }} ({{ $jabatanTerpilih->code }})</h4>
        </div>
        <a href="{{ route('surat.step1.show') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <hr>
        @if ($jenisSurat->isEmpty())
            <div class="alert alert-warning text-center">
                Tidak ada data jenis surat yang dipilih
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach ($jenisSurat as $item)
                <div class="col">
                    <form action="{{ route('surat.step2.store') }}" method="post">
                        @csrf
                        <input type="hidden" name="letter_code_jenis_surat_id" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-card h-100 w-100 p-3 border border-dark">
                            <div class="aspect-ratio aspect-ratio-1x1 d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title fw-bold text-primary">{{ $item->code }}</h3>
                                <p class="card-text mb-0">{{ $item->name }}</p>
                            </div>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection