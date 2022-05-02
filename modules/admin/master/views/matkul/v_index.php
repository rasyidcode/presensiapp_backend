<?= $this->extend('\Modules\Shared\Layouts\Views\dashboard\layout') ?>

<?= $this->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?=route_to('master.matkul.add')?>" class="btn btn-primary btn-xs mr-2">
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
<?= $this->endSection() ?>

<?=$this->section('custom-js')?>
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
                type: 'post' ,
                url: '<?=route_to('master.matkul.get-data')?>',
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
        columns: [
            { targets: 0, orderable: false, searchable: false },
            { targets: 1, orderable: true,  searchable: true },
            { targets: 2, orderable: true,  searchable: true },
            { targets: 3, orderable: true,  searchable: false },
            { targets: 4, orderable: false, searchable: false }
        ],
        drawCallback: function(settings) {}
    });
});
</script>
<?=$this->endSection() ?>