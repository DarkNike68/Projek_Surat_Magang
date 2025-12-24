@extends('layouts.app-sidebar')

@section('title', 'Dashboard')

@push('styles')
<style>
    .btn-xs {
        --bs-btn-padding-y: .1rem;
        --bs-btn-padding-x: .4rem;
        --bs-btn-font-size: .50rem;
        line-height: 0.5;
        letter-spacing: 0.8px;   
    }
    .table-dark th {
        text-align: center;
    }
</style>

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading">Upload Gagal!</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Daftar Surat</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        @can('view-admin-menu')
                            <th>Pembuat</th>
                        @endcan
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                        <th>File</th>
                        @can('view-admin-menu')
                            <th>Aksi</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surats as $key => $surat)
                        <tr>
                            <td class="text-center">{{ $surats->firstItem() + $key }}</td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->perihal }}</td>
                            @can('view-admin-menu')
                                <td>{{ $surat->user->full_name ?? 'N/A' }}</td>
                            @endcan
                            <td>{{ $surat->created_at->translatedFormat('d F Y H:i') }}</td>
                            <td class="text-center">
                                @php
                                    $statusClass = [
                                        'Belum upload' => 'bg-secondary',
                                        'Menunggu Persetujuan' => 'bg-warning text-dark',
                                        'Revisi' => 'bg-danger',
                                        'Disahkan' => 'bg-success',
                                        'Diarsipkan' => 'bg-info'
                                    ][$surat->status] ?? 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $surat->status }}</span>

                                @if ($surat->status == 'Revisi' && !empty($surat->catatan))
                                    <div class="mt-3"> 
                                        <button type="button" class="btn btn-link text-danger p-0"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#catatanModal" 
                                                data-surat-catatan="{{ $surat->catatan }}"
                                                title="Lihat Catatan Revisi">
                                            <i class="fas fa-comment-alt fa-lg"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>


                            <td class="text-center">
                                @if(Auth::user()->id_users == $surat->user_id)
                                    @if($surat->status == 'Belum upload' || $surat->status == 'Revisi')
                                        <button type="button" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-target="#uploadModal" data-surat-id="{{ $surat->id }}">
                                            <i class="fas fa-upload me-1"></i> 
                                            {{ $surat->status == 'Revisi' ? 'Upload Ulang' : 'Upload Draf' }}
                                        </button>
                                    @endif
                                @endif

                                @if ($surat->file_path)
                                    <a href="{{ asset('storage/' . $surat->file_path) }}" target="_blank" class="d-block text-success small text-center">
                                        <i class="fas fa-check-circle"></i> Lihat File
                                    </a>
                                @endif
                            </td>


                            @can('view-admin-menu')
                            <td class="text-center">
                                @if($surat->status == 'Menunggu Persetujuan')
                                    <button type="button" class="btn btn-xs btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#adminUpdateModal" 
                                            data-surat-id="{{ $surat->id }}" 
                                            data-surat-catatan="{{ $surat->catatan }}" 
                                            data-surat-status="{{ $surat->status }}">
                                        <i class="fas fa-edit me-1"></i> Verifikasi
                                    </button>
                                @endif

                                @if($surat->status == 'Disahkan')
                                    <button type="button" class="btn btn-xs btn-success" data-bs-toggle="modal" data-bs-target="#arsipModal" data-surat-id="{{ $surat->id }}">
                                        <i class="fas fa-archive me-1"></i> Arsipkan
                                    </button>
                                @endif

                                @if($surat->status == 'Diarsipkan' && $surat->outner)
                                    <div class="text-muted small text-center mt-2">
                                        <b>Lokasi:</b><br>
                                        {{ $surat->outner->skat->rak->name }} ({{$surat->outner->skat->rak->year}}) / {{ $surat->outner->skat->name }} / {{ $surat->outner->name }}
                                    </div>
                                @endif
                            </td>
                            @endcan
                        </tr>
                    @empty
                        <td colspan="{{ 'view-admin-menu' ? 7 : 6 }}" class="text-center">Belum ada data surat.</td>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $surats->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_surat" class="form-label">Pilih file PDF Anda (Maks. 2MB)</label>
                        <input class="form-control" type="file" name="file_surat" required=".pdf,application/pdf">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="adminUpdateModal" tabindex="-1" aria-labelledby="adminUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminUpdateModalLabel">Update Status & Catatan Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="adminUpdateForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Aksi:</label>
                        <div class="btn-group w-100" role="group">
                            {{-- Tombol untuk Revisi --}}
                            <input type="radio" class="btn-check" name="status" id="statusRevisi" value="Revisi" autocomplete="off">
                            <label class="btn btn-outline-danger" for="statusRevisi">Revisi</label>

                            {{-- Tombol untuk Disahkan --}}
                            <input type="radio" class="btn-check" name="status" id="statusDisahkan" value="Disahkan" autocomplete="off">
                            <label class="btn btn-outline-success" for="statusDisahkan">Disahkan</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="catatanAdmin" class="form-label">Catatan (Wajib jika Revisi)</label>
                        <textarea class="form-control" id="catatanAdmin" name="catatan" rows="3" placeholder="Berikan catatan jika ada..."></textarea>
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

<div class="modal fade" id="catatanModal" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="catatanModalLabel">Catatan Revisi dari Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="catatanText" rows="6" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="arsipModal" tabindex="-1" aria-labelledby="arsipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="arsipModalLabel">Arsipkan Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="arsipForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rak_select" class="form-label">Pilih Rak</label>
                        <select class="form-select" id="rak_select" name="rak_id" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="skat_select" class="form-label">Pilih Skat</label>
                        <select class="form-select" id="skat_select" name="skat_id" required disabled></select>
                    </div>
                    <div class="mb-3">
                        <label for="outner_select" class="form-label">Pilih Outner (Bisa dicari)</label>
                        <select class="form-select" id="outner_select" name="outner_id" required disabled></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Arsip</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var uploadModal = document.getElementById('uploadModal');
        uploadModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var suratId = button.getAttribute('data-surat-id');
            var form = document.getElementById('uploadForm');
            var actionUrl = "{{ route('dashboard.surat.upload', ['surat' => ':id']) }}".replace(':id', suratId);
            form.setAttribute('action', actionUrl);
        });

        var adminModal = document.getElementById('adminUpdateModal');
        if (adminModal) {
            adminModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var suratId = button.getAttribute('data-surat-id');
                var suratCatatan = button.getAttribute('data-surat-catatan');
                var suratStatus = button.getAttribute('data-surat-status');
                
                var form = document.getElementById('adminUpdateForm');
                var actionUrl = "{{ url('admin/surat') }}/" + suratId + "/update";
                form.setAttribute('action', actionUrl);
                
                document.getElementById('catatanAdmin').value = suratCatatan;
                document.querySelectorAll('input[name="status"]').forEach(radio => radio.checked = false);

                const radioToSelect = document.querySelector(`input[name="status"][value="${suratStatus}"]`);
                if (radioToSelect) {
                    radioToSelect.checked = true;
                }
            });
        }
        var catatanModal = document.getElementById('catatanModal');
        if (catatanModal) {
            catatanModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var suratCatatan = button.getAttribute('data-surat-catatan');
                
                var catatanTextarea = document.getElementById('catatanText');
                catatanTextarea.value = suratCatatan;
            });
        }

        $('#arsipModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var suratId = button.data('surat-id');
            var form = $('#arsipForm');
            form.attr('action', `{{ url('admin/surat') }}/${suratId}/arsip`);

            var rakSelect = $('#rak_select'), 
                skatSelect = $('#skat_select'), 
                outnerSelect = $('#outner_select');
            
            skatSelect.empty().append('<option value="">Pilih Rak Dahulu</option>').prop('disabled', true);
            outnerSelect.empty().append('<option value="">Pilih Skat Dahulu</option>').prop('disabled', true);
            
            outnerSelect.select2({ 
                theme: 'bootstrap-5', 
                dropdownParent: $('#arsipModal'), 
                placeholder: 'Pilih Outner' 
            });

            rakSelect.empty().append('<option value="">Memuat...</option>').prop('disabled', true);
            $.get("{{ route('admin.api.raks') }}", function(data) {
                rakSelect.empty().append('<option value="">Pilih Rak</option>').prop('disabled', false);
                data.forEach(rak => rakSelect.append(`<option value="${rak.id}">${rak.name}</option>`));
            });
        });

        $('#rak_select').on('change', function() {
            var rakId = $(this).val();
            var skatSelect = $('#skat_select');
            skatSelect.empty().append('<option value="">Memuat...</option>').prop('disabled', true);
            $('#outner_select').empty().append('<option value="">Pilih Skat Dahulu</option>').prop('disabled', true).trigger('change');

            if (rakId) {
                $.get(`/admin/api/rak/${rakId}/skats`, data => { 
                    skatSelect.empty().append('<option value="">Pilih Skat</option>').prop('disabled', false);
                    data.forEach(skat => skatSelect.append(`<option value="${skat.id}">${skat.name}</option>`));
                });
            } else {
                skatSelect.empty().append('<option value="">Pilih Rak Dahulu</option>').prop('disabled', true);
            }
        });

        $('#skat_select').on('change', function() {
            var skatId = $(this).val();
            var outnerSelect = $('#outner_select');
            outnerSelect.empty().append('<option value="">Memuat...</option>').prop('disabled', true);

            if (skatId) {
                $.get(`/admin/api/skat/${skatId}/outners`, data => { 
                    outnerSelect.empty().append('<option value="">Pilih Outner</option>').prop('disabled', false);
                    data.forEach(outner => outnerSelect.append(`<option value="${outner.id}">${outner.name}</option>`));
                });
            } else {
                outnerSelect.empty().append('<option value="">Pilih Skat Dahulu</option>').prop('disabled', true);
            }
        });
    });
</script>
@endpush