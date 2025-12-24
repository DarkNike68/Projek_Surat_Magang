@extends('layouts.app-sidebar')

@section('title', 'Matriks Hak Akses per Unit')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Atur izin Baca (Read) dan Tulis (Write) untuk setiap Unit terhadap setiap Kategori Dokumen.
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="min-width: 800px;">
                <thead class="table-light">
                    <tr class="text-center">
                        <th class="align-middle" style="width: 40%;">Kategori Dokumen</th>
                        @foreach ($units as $unit)
                            <th class="align-middle">{{ $unit->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        @include('permissions._category-row', [
                            'category' => $category,
                            'units' => $units,
                            'level' => 0,
                            'permissions' => $permissions
                        ])
                    @empty
                        <tr>
                            <td colspan="{{ count($units) + 1 }}" class="text-center text-muted p-5">
                                Belum ada kategori dokumen yang dibuat. Silakan tambahkan di menu "Kategori Dokumen".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Pengecualian Izin -->
<div class="modal fade" id="exceptionModal" tabindex="-1" aria-labelledby="exceptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exceptionModalLabel">Atur Pengecualian Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Atur izin perorangan untuk Unit: <strong id="modalUnitName"></strong><br>
                    Pada Kategori: <strong id="modalCategoryName"></strong></p>
                <hr>
                <div id="userListContainer" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center">Memuat...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="saveExceptionsBtn" class="btn btn-primary">Simpan Pengecualian</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listener untuk checkbox di tabel utama (menggunakan event delegation)
    document.querySelector('.table').addEventListener('change', function(e) {
        if (e.target.classList.contains('permission-check')) {
            const checkbox = e.target;
            const unitId = checkbox.dataset.unitId;
            const categoryId = checkbox.dataset.categoryId;
            const permissionType = checkbox.dataset.permissionType;
            const isChecked = checkbox.checked;

            fetch('{{ route("admin.permissions.update") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    unit_id: unitId,
                    category_id: categoryId,
                    permission_type: permissionType,
                    is_checked: isChecked
                })
            })
            .then(response => {
                if (!response.ok) {
                    alert('Gagal menyimpan perubahan. Silakan coba lagi.');
                    checkbox.checked = !isChecked; // Kembalikan ke state semula jika gagal
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan jaringan.');
                checkbox.checked = !isChecked;
            });
        }
    });

    // Logika untuk Modal Pengecualian
    const exceptionModal = document.getElementById('exceptionModal');
    const saveExceptionsBtn = document.getElementById('saveExceptionsBtn');
    let currentUnitId, currentCategoryId;

    exceptionModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        currentUnitId = button.dataset.unitId;
        currentCategoryId = button.dataset.categoryId;
        document.getElementById('modalUnitName').textContent = button.dataset.unitName;
        document.getElementById('modalCategoryName').textContent = button.dataset.categoryName;

        const userListContainer = document.getElementById('userListContainer');
        userListContainer.innerHTML = '<p class="text-center py-4">Memuat...</p>';

        // Ambil daftar user untuk unit dan kategori ini
        fetch(`{{ route('admin.permissions.searchUsers') }}?unit_id=${currentUnitId}&category_id=${currentCategoryId}`)
            .then(response => response.json())
            .then(users => {
                userListContainer.innerHTML = '';
                if (users.length > 0) {
                    users.forEach(user => {
                        const exception = user.permission_exceptions[0];
                        // Null berarti ikut aturan unit, jadi kita tampilkan sebagai false (tidak dicentang)
                        const canRead = exception ? exception.can_read === true : false;
                        const canWrite = exception ? exception.can_write === true : false;

                        const userHtml = `
                            <div class="d-flex justify-content-between align-items-center p-2 border-bottom user-permission-row" data-user-id="${user.id}">
                                <span>${user.name}</span>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" ${canRead ? 'checked' : ''} id="ex-read-${user.id}">
                                        <label class="form-check-label" for="ex-read-${user.id}">Baca</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" ${canWrite ? 'checked' : ''} id="ex-write-${user.id}">
                                        <label class="form-check-label" for="ex-write-${user.id}">Tulis</label>
                                    </div>
                                </div>
                            </div>`;
                        userListContainer.insertAdjacentHTML('beforeend', userHtml);
                    });
                } else {
                    userListContainer.innerHTML = '<p class="text-center text-muted py-4">Tidak ada user di unit ini.</p>';
                }
            });
    });

    saveExceptionsBtn.addEventListener('click', function() {
        const permissionRows = document.querySelectorAll('.user-permission-row');
        const permissions = [];
        permissionRows.forEach(row => {
            permissions.push({
                user_id: row.dataset.userId,
                can_read: row.querySelector(`#ex-read-${row.dataset.userId}`).checked,
                can_write: row.querySelector(`#ex-write-${row.dataset.userId}`).checked,
            });
        });

        fetch('{{ route("admin.permissions.updateException") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                category_id: currentCategoryId,
                permissions: permissions
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message || 'Pengecualian berhasil disimpan!');
                const modal = bootstrap.Modal.getInstance(exceptionModal);
                modal.hide();
            } else {
                alert('Gagal menyimpan data pengecualian.');
            }
        });
    });
});
</script>
@endpush