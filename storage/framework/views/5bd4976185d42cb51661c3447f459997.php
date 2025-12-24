<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>



<?php $__env->startSection('title', 'Pilih Jenis Surat'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Langkah 2: Pilih Jenis Surat</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-baseline mb-4">
            <p class="text-muted me-2 mb-0">Ditujukan Kepada:</p>
            <h4 class="fw-bold mb-0"><?php echo e($jabatanTerpilih->name); ?> (<?php echo e($jabatanTerpilih->code); ?>)</h4>
        </div>
        <a href="<?php echo e(route('surat.step1.show')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <hr>
        <?php if($jenisSurat->isEmpty()): ?>
            <div class="alert alert-warning text-center">
                Tidak ada data jenis surat yang dipilih
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php $__currentLoopData = $jenisSurat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col">
                    <form action="<?php echo e(route('surat.step2.store')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="letter_code_jenis_surat_id" value="<?php echo e($item->id); ?>">
                        <button type="submit" class="btn btn-card h-100 w-100 p-3 border border-dark">
                            <div class="aspect-ratio aspect-ratio-1x1 d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title fw-bold text-primary"><?php echo e($item->code); ?></h3>
                                <p class="card-text mb-0"><?php echo e($item->name); ?></p>
                            </div>
                        </button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/surat/step2_jenissurat.blade.php ENDPATH**/ ?>