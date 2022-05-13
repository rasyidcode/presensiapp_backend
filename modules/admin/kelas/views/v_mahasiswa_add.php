<?= $renderer->extend('modules/shared/layouts/views/dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Dosen Pengajar</dt>
                            <dd class="col-sm-8"><?= $kelasInfo->dosen_pengajar ?></dd>
                            <dt class="col-sm-4">Mata Kuliah</dt>
                            <dd class="col-sm-8"><?= $kelasInfo->nama_matkul ?></dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?=route_to('kelas.mahasiswa', $kelasId)?>" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-arrow-alt-circle-left"></i>&nbsp;&nbsp;Back
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty(session()->getFlashdata('error'))) : ?>
                            <div class="alert alert-danger" role="alert">
                                <ul style="margin-bottom: 0 !important;">
                                    <?php foreach (session()->getFlashdata('error') as $error) : ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty(session()->getFlashdata('success'))) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= route_to('kelas.mahasiswa.create', $kelasId) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="mahasiswa">Mahasiswa</label>
                                <!-- todo: change this to select2, so we can select multiple students -->
                                <select name="mahasiswa" class="form-control">
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    <?php foreach($mahasiswaList as $mahasiswaListItem): ?>
                                        <option value="<?=$mahasiswaListItem->id?>"><?=$mahasiswaListItem->nim?> - <?=$mahasiswaListItem->nama_lengkap?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<?= $renderer->endSection() ?>

<?= $renderer->section('custom-js') ?>
<script></script>
<?= $renderer->endSection() ?>