

<?php $__env->startSection('title', 'Konfirmasi dan Buat Surat'); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Langkah 3: Konfirmasi Detail Surat</h5>
            </div>
            <div class="card-body">
                 <?php if($errors->any()): ?>
                    <div class="alert alert-danger mb-4">
                        <h5 class="alert-heading">Terjadi Kesalahan!</h5>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Jabatan Yang Dituju:</label>
                    <p class="form-control-plaintext"><?php echo e($jabatanTerpilih->name); ?> (<?php echo e($jabatanTerpilih->code); ?>)</p>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Jenis Surat:</label>
                    <p class="form-control-plaintext"><?php echo e($jenisSuratTerpilih->name); ?> (<?php echo e($jenisSuratTerpilih->code); ?>)</p>
                </div>
                <hr>
                <form action="<?php echo e(route('surat.generate')); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat nomor surat dengan data ini?');">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="perihal" class="form-label fw-bold">Perihal:</label>
                        <input type="text" class="form-control" id="perihal" name="perihal" placeholder="Contoh: Undangan Rapat" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?php echo e(route('surat.step2.show')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                        <div>
                            <?php if($jenisSuratTerpilih->template_file): ?>
                                <a href="<?php echo e(route('surat.template.download')); ?>" class="btn btn-info">
                                    <i class="fas fa-download me-2"></i>Download Template
                                </a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Konfirmasi & Buat Nomor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> 

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Preview</h5>
            </div>
            <div class="card-body">
                <button type="button" id="btn-preview" class="btn btn-outline-primary w-100">
                    <i class="fas fa-eye me-2"></i>Lihat Preview Nomor
                </button>
                <hr>
                <div id="preview-result" class="text-center">
                    <p class="text-muted">Nomor surat akan tampil di sini...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">History Log</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nomor Surat</th>
                                <th>Perihal</th>
                                <th style="width: 20%;">Tanggal Dibuat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $historySurat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($key + 1); ?></td>
                                    <td><?php echo e($surat->nomor_surat); ?></td>
                                    <td><?php echo e($surat->perihal); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y H:i')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada riwayat surat untuk kombinasi ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const previewButton = document.getElementById('btn-preview');
        const previewResult = document.getElementById('preview-result');

        previewButton.addEventListener('click', function () {
            previewResult.innerHTML = '<p class="text-muted">Memuat...</p>';

            fetch("<?php echo e(route('surat.preview')); ?>")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal memuat preview.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.nomor_surat) {
                        previewResult.innerHTML = `<h5 class="fw-bold text-primary">${data.nomor_surat}</h5>`;
                    } else {
                        previewResult.innerHTML = `<p class="text-danger">${data.error || 'Terjadi kesalahan.'}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewResult.innerHTML = '<p class="text-danger">Gagal memuat preview. Coba lagi.</p>';
                });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\projek-surat\resources\views/admin/surat/step3_final.blade.php ENDPATH**/ ?>