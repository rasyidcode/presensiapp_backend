<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?=route_to('mahasiswa.list')?>" class="btn btn-primary btn-sm mr-2">
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
                        <form action="<?= route_to('mahasiswa.update', $editData->id) ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="nim">NIM</label>
                                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required value="<?=$editData->nim?>">
                            </div>
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap" required value="<?=$editData->nama_lengkap?>">
                            </div>
                            <div class="form-group">
                                <label for="id_jurusan">Jurusan</label>
                                <select name="id_jurusan" class="form-control">
                                    <option value="">-- Pilih Jurusan --</option>
                                    <?php foreach($jurusanList as $jurusanListItem): ?>
                                        <option value="<?=$jurusanListItem->id?>" <?=($editData->id_jurusan == $jurusanListItem->id ? 'selected' : '')?>>
                                            <strong><?=$jurusanListItem->kode?></strong> - <?=$jurusanListItem->nama?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tahun_masuk">Tahun Masuk</label>
                                <select name="tahun_masuk" class="form-control">
                                    <option value="">-- Pilih Tahun Masuk --</option>
                                    <?php for($year = date('Y'); $year > 2010; $year--): ?>
                                        <option value="<?=$year?>" <?=($editData->tahun_masuk == $year ? 'selected' : '')?>><?=$year?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Jenis Kelamin</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" <?=($editData->jenis_kelamin == 'L' ? 'checked' : '')?>>
                                    <label class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" <?=($editData->jenis_kelamin == 'P' ? 'checked' : '')?>>
                                    <label class="form-check-label">Perempuan</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan Alamat..."><?=$editData->alamat?></textarea>
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