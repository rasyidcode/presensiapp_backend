<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?= route_to('kelas.add') ?>" class="btn btn-primary btn-xs mr-2">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Kelas
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="data-kelas" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Matkul</th>
                                    <th>Dosen Pengajar</th>
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
<script>
    $(function() {
        var table = $('#data-kelas').DataTable({
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
                    url: '<?= route_to('kelas.get-data') ?>',
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
                    orderable: true,
                    searchable: true
                },
                {
                    targets: 2,
                    orderable: true,
                    searchable: true
                },
                {
                    targets: 3,
                    orderable: true,
                    searchable: true
                },
                {
                    targets: 4,
                    orderable: true,
                    searchable: false
                },
                {
                    targets: 5,
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function(settings) {}
        });
    });
</script>
<?= $renderer->endSection() ?>