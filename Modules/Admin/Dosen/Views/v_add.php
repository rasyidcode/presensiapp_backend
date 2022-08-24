<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?=route_to('dosen.list')?>" class="btn btn-primary btn-sm mr-2">
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
                        <form action="<?= route_to('dosen.create') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="nip">NIP</label>
                                <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                            </div>
                            <div class="form-group">
                                <label for="tahun_masuk">Tahun Masuk</label>
                                <select name="tahun_masuk" class="form-control">
                                    <option value="">-- Pilih Tahun Masuk --</option>
                                    <?php for($year = date('Y'); $year > 2010; $year--): ?>
                                        <option value="<?=$year?>"><?=$year?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Jenis Kelamin</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" checked>
                                    <label class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P">
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan Alamat..."></textarea>
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