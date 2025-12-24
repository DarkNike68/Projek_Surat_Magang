<?php $__env->startPush('styles'); ?>
<style>
    .btn-card{
        border: 1px solid #dee2e6;
        background-color: white;
        color: #212529;
        text-align: center;
        transition: all 0.2s ease-in-out;
    }
    .btn-card-hover{
        transform: translate(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
        border-color: #0d6efd;
    }
</style>
<?php $__env->stopPush(); ?>



<?php $__env->startSection('title', 'Pilih Jabatan Tujuan Surat'); ?>

<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Langkah 1: Pilih Jabatan</h5>
    </div>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                Terjadi Kesalahan. Silahkan coba lagi.
            </div>
        <?php endif; ?>

        <?php if($jabatan->isEmpty()): ?>
            <div class="alert alert-warning text-center">
                Tidak ada data Jabatan yang dituju
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php $__currentLoopData = $jabatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <form action="<?php echo e(route('surat.step1.store')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="letter_code_jabatan_id" value="<?php echo e($item->id); ?>">
                            <button type="submit" class="btn btn-card w-100 p-3 border border-dark">
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
<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/surat/step1_jabatan.blade.php ENDPATH**/ ?>