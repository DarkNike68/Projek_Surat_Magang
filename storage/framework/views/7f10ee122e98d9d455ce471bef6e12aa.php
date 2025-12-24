

<?php $__env->startSection('title', 'Manajemen Arsip'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session('success')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>


<div class="row">
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rak</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.arsip.store')); ?>" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="rak">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Rak Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                <ul class="list-group">
                    <?php $__empty_1 = true; $__currentLoopData = $raks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?php echo e($rak->name); ?> <span class="badge bg-secondary"><?php echo e($rak->year); ?></span></span>
                            <div>
                                <button type="button" class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-update-url="<?php echo e(route('admin.arsip.update', ['type' => 'rak', 'id' => $rak->id])); ?>"
                                        data-current-name="<?php echo e($rak->name); ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo e(route('admin.arsip.destroy', ['type' => 'rak', 'id' => $rak->id])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus rak ini? Semua skat dan outner di dalamnya akan ikut terhapus.')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item text-muted">Belum ada rak.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Skat</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.arsip.store')); ?>" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="skat">
                    <div class="mb-3">
                        <select name="rak_id" class="form-select" required>
                            <option value="">-- Pilih Rak --</option>
                            <?php $__currentLoopData = $raks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($rak->id); ?>"><?php echo e($rak->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Skat Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                <?php $__currentLoopData = $raks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <h6 class="mt-3">Di Rak: <?php echo e($rak->name); ?> (<?php echo e($rak->year); ?>)</h6>
                    <ul class="list-group">
                        <?php $__empty_1 = true; $__currentLoopData = $rak->skats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                
                                <div>
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-update-url="<?php echo e(route('admin.arsip.update', ['type' => 'skat', 'id' => $skat->id])); ?>"
                                            data-current-name="<?php echo e($skat->name); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="<?php echo e(route('admin.arsip.destroy', ['type' => 'skat', 'id' => $skat->id])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus skat ini? Semua outner di dalamnya akan ikut terhapus.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="list-group-item text-muted">Belum ada skat.</li>
                        <?php endif; ?>
                    </ul>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Outner</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.arsip.store')); ?>" method="POST" class="mb-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="type" value="outner">
                    <div class="mb-3">
                        <select name="skat_id" class="form-select" required>
                            <option value="">-- Pilih Skat --</option>
                            <?php $__currentLoopData = $raks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <optgroup label="Rak: <?php echo e($rak->name); ?>">
                                    <?php $__currentLoopData = $rak->skats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($skat->id); ?>"><?php echo e($skat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Nama Outner Baru" required>
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </div>
                </form>
                 <?php $__currentLoopData = $raks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $rak->skats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <h6 class="mt-3">Di Skat: <?php echo e($skat->name); ?> (<?php echo e($rak->name); ?> - <?php echo e($rak->year); ?>)</h6>
                        <ul class="list-group">
                            <?php $__empty_1 = true; $__currentLoopData = $skat->outners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $outner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo e($outner->name); ?>

                                    <div>
                                        <button type="button" class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-update-url="<?php echo e(route('admin.arsip.update', ['type' => 'outner', 'id' => $outner->id])); ?>"
                                                data-current-name="<?php echo e($outner->name); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="<?php echo e(route('admin.arsip.destroy', ['type' => 'outner', 'id' => $outner->id])); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus outner ini?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-muted">Belum ada outner.</li>
                            <?php endif; ?>
                        </ul>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>


<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Daftar Surat Terarsip</h5>
        <form action="<?php echo e(route('admin.arsip.index')); ?>" method="GET" class="d-flex align-items-center">
            <label for="year_filter" class="form-label me-2 mb-0">Tahun:</label>
            <select name="year" id="year_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php $__empty_1 = true; $__currentLoopData = $availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <option value="<?php echo e($year); ?>" <?php echo e($year == $selectedYear ? 'selected' : ''); ?>>
                        <?php echo e($year); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <option><?php echo e(date('Y')); ?></option>
                <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $suratsDiarsipkan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($suratsDiarsipkan->firstItem() + $key); ?></td>
                            <td><?php echo e($surat->nomor_surat); ?></td>
                            <td><?php echo e($surat->perihal); ?></td>
                            <td><?php echo e($surat->user->full_name ?? 'N/A'); ?></td>
                            <td class="text-center">
                                <?php if($surat->outner): ?>
                                    <span class="badge bg-info">
                                        <?php echo e($surat->outner->skat->rak->name); ?> (<?php echo e($surat->outner->skat->rak->year); ?>) / <?php echo e($surat->outner->skat->name); ?> / <?php echo e($surat->outner->name); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($surat->file_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $surat->file_path)); ?>" target="_blank" class="btn btn-xs btn-success">
                                        <i class="fas fa-eye"></i> Lihat File
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form action="<?php echo e(route('admin.surat.unarchive', $surat->id)); ?>" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengarsipan surat ini?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-undo"></i> Batalkan Arsip
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada surat yang diarsipkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($suratsDiarsipkan->appends(['year' => $selectedYear])->links()); ?>

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
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/arsip/index.blade.php ENDPATH**/ ?>