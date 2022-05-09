<?= $this->extend('\Modules\Shared\Layouts\Views\dashboard\layout') ?>

<?= $this->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card card-outline card-primary">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">Dosen Pengajar</dt>
                            <dd class="col-sm-8"><?= $kelasInfo->dosen_pengajar ?></dd>
                            <dt class="col-sm-4">Mata Kuliah</dt>
                            <dd class="col-sm-8"><?= $kelasInfo->nama_matkul ?></dd>
                        </dl>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <a href="<?= route_to('kelas.mahasiswa.add') ?>" class="btn btn-primary btn-xs mr-2">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Daftarkan Mahasiswa
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="data-kelas-mahasiswa" class="table table-bordered table-hover table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIM</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jurusan</th>
                                    <th>Jenis Kelamin</th>
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

<?= $this->section('custom-js') ?>
<script>
    $(function() {
        var table = $('#data-kelas-mahasiswa').DataTable({
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
                    url: '<?= route_to('kelas.mahasiswa.get-data', $kelasInfo->id) ?>',
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
                    searchable: true
                },
                {
                    targets: 2,
                    orderable: false,
                    searchable: true
                },
                {
                    targets: 3,
                    orderable: false,
                    searchable: true
                },
                {
                    targets: 4,
                    orderable: false,
                    searchable: true
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
<?= $this->endSection() ?>