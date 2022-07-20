<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?= route_to('user.add') ?>" class="btn btn-primary btn-xs mr-2">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah User
                        </a>
                    </div>
                    <div class="card-body">
                        <table id="data-user" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Last Login</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<?= $renderer->endSection() ?>

<?= $renderer->section('custom-js') ?>
<script>
    $(function() {
        var table = $('#data-user').DataTable({
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
                    url: '<?= route_to('user.get-data') ?>',
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
                    orderable: true,
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
    });
</script>
<?= $renderer->endSection() ?>