<?= $this->extend('\Modules\Shared\Layouts\Views\dashboard\layout') ?>

<?= $this->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?=route_to('master.jurusan.list')?>)" class="btn btn-primary btn-sm mr-2">
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
                        <form action="<?= route_to('master.jurusan.create') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="kode_jurusan">Kode Jurusan</label>
                                <input type="text" name="kode_jurusan" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_jurusan">Nama Jurusan</label>
                                <input type="text" name="nama_jurusan" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<?= $this->endSection() ?>

<?= $this->section('custom-js') ?>
<script></script>
<?= $this->endSection() ?>