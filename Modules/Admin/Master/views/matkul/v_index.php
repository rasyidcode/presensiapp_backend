<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?= route_to('master.matkul.add') ?>" class="btn btn-primary btn-xs mr-2">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Matkul
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="data-matkul" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Matkul</th>
                                    <th>Nama Matkul</th>
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
        var table = $('#data-matkul').DataTable({
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
                    url: '<?= route_to('master.matkul.get-data') ?>',
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
                    searchable: false
                },
                {
                    targets: 4,
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function(settings) {}
        });

        $('#data-matkul tbody').on('click', 'tr td a.btn-danger', function(e) {
            var id = $(this).data().id;
            
            if (confirm('Are you sure want to delete this data?')) {
                $.ajax({
                    url: '<?=site_url('master/matkul')?>/'+id+'/delete',
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
                        }
                    },
                    error: function(err) {
                        console.log(err);

                        alert('Something went wrong.');
                    }
                })
            }
        });
    });
</script>
<?= $renderer->endSection() ?>