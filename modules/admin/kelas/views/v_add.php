<?= $renderer->extend('modules/shared/layouts/views/dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?=route_to('kelas.list')?>" class="btn btn-primary btn-sm mr-2">
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
                        <form action="<?= route_to('kelas.create') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="matkul">Mata Kuliah</label>
                                <select name="matkul" class="form-control">
                                    <option value="">-- Pilih Mata Kuliah --</option>
                                    <?php foreach($matkulList as $matkulListItem): ?>
                                        <option value="<?=$matkulListItem->id?>"><?=$matkulListItem->kode?> - <?=$matkulListItem->nama?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dosen">Dosen Pengajar</label>
                                <select name="dosen" class="form-control">
                                    <option value="">-- Pilih Dosen Pengajar --</option>
                                    <?php foreach($dosenList as $dosenListItem): ?>
                                        <option value="<?=$dosenListItem->id?>"><?=$dosenListItem->nama_lengkap?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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