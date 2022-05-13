<?= $renderer->extend('modules/shared/layouts/views/dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a id="btn-back" href="<?= route_to('jadwal.list') ?>" class="btn btn-primary btn-sm mr-2">
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
                        <form action="<?= route_to('jadwal.create') ?>" method="post">
                            <?= csrf_field() ?>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <select name="kelas" class="form-control">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($kelasList as $kelasListItem) : ?>
                                        <option value="<?= $kelasListItem->id ?>"><?= $kelasListItem->kelas ?> - <?= $kelasListItem->matkul ?> - <?= $kelasListItem->dosen ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal">Tanggal:</label>
                                <div class="input-group date" id="tanggal" data-target-input="nearest">
                                    <input type="text" name="tanggal" class="form-control datetimepicker-input" data-target="#tanggal" />
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="beginTime">Jam Mulai: </label>
                                <div class="input-group clockpicker-begin-time">
                                    <input type="text" name="beginTime" class="form-control" value="08:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="endTime">Jam Selesai: </label>
                                <div class="input-group clockpicker-end-time">
                                    <input type="text" name="endTime" class="form-control" value="17:00">
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                </div>
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
<!-- Moment JS -->
<script src="<?= site_url('adminlte3/plugins/moment/moment.min.js') ?>"></script>
<!-- Temposdominus Bootstrap 4 -->
<script src="<?= site_url('adminlte3/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<!-- Jquery Clock Picker -->
<script src="http://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script>
    $(function() {
        $('#tanggal').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('.clockpicker-begin-time').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Pilih'
        });
        $('.clockpicker-end-time').clockpicker({
            placement: 'top',
            align: 'left',
            donetext: 'Pilih'
        });
    });
</script>
<?= $renderer->endSection() ?>