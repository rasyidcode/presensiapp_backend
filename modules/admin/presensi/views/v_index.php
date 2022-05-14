<?= $renderer->extend('modules/shared/layouts/views/dashboard/layout') ?>

<?= $renderer->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <form id="filterData" class="form-horizontal">
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-2 offset-sm-2 col-form-label">Tanggal:</label>
                                <div class="input-group date col-sm-8" id="tanggal" data-target-input="nearest">
                                    <input type="text" name="tanggal" class="form-control datetimepicker-input" data-target="#tanggal" />
                                    <div class="input-group-append" data-target="#tanggal" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kelas" class="col-sm-2 offset-sm-2 col-form-label">Kelas : </label>
                                <div class="col-sm-8">
                                    <select name="kelas" class="form-control">
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php foreach ($kelasList as $kelasListItem) : ?>
                                            <option value="<?= $kelasListItem->id ?>"><?= $kelasListItem->kelas ?> - <?= $kelasListItem->matkul ?> - <?= $kelasListItem->dosen ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-2 offset-sm-2"></div>
                                <div class="col-8">
                                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="data-presensi" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mahasiswa</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Status Presensi</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $renderer->endSection() ?>

<?= $renderer->section('custom-js') ?>
<!-- Moment JS -->
<script src="<?= site_url('adminlte3/plugins/moment/moment.min.js') ?>"></script>
<!-- Temposdominus Bootstrap 4 -->
<script src="<?= site_url('adminlte3/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script>
    $(function() {
        var table = $('#data-presensi').DataTable({
            dom: 'lrtip',
            searching: true,
            responsive: true,
            pageLength: 10,
            processing: true,
            serverSide: true,
            order: [],
            ajax: function(data, callback, settings) {
                var data = {
                    ...data,
                    ['<?= csrf_token() ?>']: '<?= csrf_hash() ?>'
                }
                $.ajax({
                    type: 'post',
                    url: '<?= route_to('presensi.get-data') ?>',
                    data: data,
                    success: function(res) {
                        console.log(res);
                        callback(res);
                    },
                    error: function(err) {
                        console.log(err);
                        callback([]);
                    }
                });
            },
            columns: [{
                    targets: 0,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 1,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 2,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 3,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 4,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 5,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 6,
                    orderable: false,
                    searchable: false
                },
            ],
            drawCallback: function(settings) {}
        });
        $('#tanggal').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        var currDate = new Date();
        var tanggal = currDate.getFullYear() + '-' + ((currDate.getMonth() + 1).toString().length < 2 ? "0" + (currDate.getMonth() + 1) : (currDate.getMonth() + 1)) + '-' + (currDate.getDate().toString().length < 2 ? "0" + currDate.getDate() : currDate.getDate());
        $('#tanggal').find('input').val(tanggal);
        $('#filterData').submit(function(e) {
            e.preventDefault();
            var data = $(this).serialize().split('&');
            var date = data[0].split('=')[1];
            var kelas = data[1].split('=')[1];
            table.search(date + '|' + kelas).draw();
        });
    });
</script>
<?= $renderer->endSection() ?>