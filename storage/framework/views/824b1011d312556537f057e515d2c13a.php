

<?php $__env->startSection('title', 'Riwayat Surat'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Seluruh Riwayat Pembuatan Surat</h5>
        
        <form action="<?php echo e(route('riwayat.index')); ?>" method="GET" class="d-flex align-items-center">
            <label for="year_filter" class="form-label me-2 mb-0">Tahun:</label>
            <select name="year" id="year_filter" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
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
                    <?php $__empty_1 = true; $__currentLoopData = $surats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($surats->firstItem() + $key); ?></td>
                            <td><?php echo e($surat->nomor_surat); ?></td>
                            <td><?php echo e($surat->perihal); ?></td>
                            <td><?php echo e($surat->user->full_name ?? 'N/A'); ?></td>
                            <td><?php echo e($surat->jabatan->name ?? 'N/A'); ?></td>
                            <td><?php echo e($surat->jenisSurat->name ?? 'N/A'); ?></td>
                            <td><?php echo e($surat->created_at->translatedFormat('d F Y H:i')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data surat yang dibuat untuk tahun <?php echo e($selectedYear); ?>.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <?php echo e($surats->appends(['year' => $selectedYear])->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/riwayat/index.blade.php ENDPATH**/ ?>