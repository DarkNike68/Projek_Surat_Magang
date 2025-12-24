

<?php $__env->startSection('title', 'Konfigurasi Kode Surat'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?php echo e($title); ?></h5>
        
        <?php if($type !== 'jabatan'): ?>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus"></i> Tambah <?php echo e($title); ?> Baru
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Tipe</th>
                        
                        <?php if($type !== 'jabatan'): ?>
                            <th scope="col">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $letterCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <th scope="row"><?php echo e($key + 1); ?></th>
                        <td><?php echo e($code->name); ?></td>
                        <td><span class="badge bg-secondary"><?php echo e($code->code); ?></span></td>
                        <td><?php echo e($code->type); ?></td>
                        
                        <?php if($type !== 'jabatan'): ?>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-btn"
                                        data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo e($code->id); ?>"
                                        data-name="<?php echo e($code->name); ?>"
                                        data-code="<?php echo e($code->code); ?>"
                                        data-type="<?php echo e($code->type); ?>"
                                        data-action="<?php echo e(route('admin.letter-codes.update', $code->id)); ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="<?php echo e(route('admin.letter-codes.destroy', $code->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kode ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Kode Surat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.letter-codes.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <input type="hidden" name="type" value="<?php echo e($type); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Kode Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Kode</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <input type="hidden" name="type" value="<?php echo e($type); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            
            const name = button.getAttribute('data-name');
            const code = button.getAttribute('data-code');
            const type = button.getAttribute('data-type');
            const action = button.getAttribute('data-action');
            
            const modalForm = editModal.querySelector('#editForm');
            modalForm.action = action;
            
            const modalNameInput = editModal.querySelector('#edit_name');
            const modalCodeInput = editModal.querySelector('#edit_code');
            const modalTypeSelect = editModal.querySelector('#edit_type');
            
            modalNameInput.value = name;
            modalCodeInput.value = code;
            modalTypeSelect.value = type;
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/letter-codes/index.blade.php ENDPATH**/ ?>