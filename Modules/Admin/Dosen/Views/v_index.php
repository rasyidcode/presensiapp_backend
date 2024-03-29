<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?= route_to('dosen.add') ?>" class="btn btn-primary btn-xs mr-2">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Dosen
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="data-dosen" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tahun Masuk</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Modified At</th>
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
        var table = $('#data-dosen').DataTable({
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
                    url: '<?= route_to('dosen.get-data') ?>',
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
                    searchable: true
                },
                {
                    targets: 5,
                    orderable: true,
                    searchable: false
                },
                {
                    targets: 7,
                    orderable: false,
                    searchable: false
                }

            ],
            drawCallback: function(settings) {}
        });

        $('#data-dosen tbody').on('click', 'tr td a.btn.btn-danger', function(e) {
            var id = $(this).data().id;

            if (confirm('Are you sure want to delete this?')) {
                $.ajax({
                    url: '<?=site_url('dosen')?>/'+id+'/delete',
                    type: 'POST',
                    data: {
                        ['<?= csrf_token() ?>']: '<?= csrf_hash() ?>'
                    },
                    success: function(res) {
                        console.log(res);
                        if (res.success) {
                            alert(res.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert('Something went wrong');
                        }
                    },
                    error: function(err) {
                        console.log(err);

                        alert('Something went wrong');
                    }
                })
            }
        });
    });
</script>
<?= $renderer->endSection() ?>