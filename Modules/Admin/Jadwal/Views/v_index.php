<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Custom Tabs -->
                <div class="card card-outline card-primary">
                    <div class="card-header d-flex p-0">
                        <div class="card-title p-3">
                            <a href="<?= route_to('jadwal.add') ?>" class="btn btn-primary btn-xs mr-2 ">
                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Jadwal
                            </a>
                        </div>
                        <ul class="nav nav-pills jadwal-days ml-auto p-2">
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="1">Senin</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="2">Selasa</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="3">Rabu</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="4">Kamis</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="5">Jumat</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="6">Sabtu</a></li>
                            <li class="nav-item"><a class="nav-link" href="#jadwal" data-toggle="tab" data-dow="0">Minggu</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form id="filter_data" class="form-horizontal">
                            <div class="form-group row">
                                <label for="searchQuery" class="col-sm-2 offset-sm-2 col-form-label">Mata Kuliah : </label>
                                <div class="col-sm-8">
                                    <select name="matkul" class="form-control">
                                        <option value="">-- Pilih Mata Kuliah --</option>
                                        <?php foreach ($matkulList as $matkulListItem) : ?>
                                            <option value="<?= $matkulListItem->id ?>"><?= $matkulListItem->kode ?> - <?= $matkulListItem->nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="searchQuery" class="col-sm-2 offset-sm-2 col-form-label">Dosen : </label>
                                <div class="col-sm-8">
                                    <select name="dosen" class="form-control">
                                        <option value="">-- Pilih Dosen --</option>
                                        <?php foreach ($dosenList as $dosenListItem) : ?>
                                            <option value="<?= $dosenListItem->id ?>"><?= $dosenListItem->nama_lengkap ?></option>
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
                        <div class="tab-content">
                            <div class="tab-pane active" id="senin">
                                <table id="data-jadwal" class="table table-bordered table-hover table-sm" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Mulai</th>
                                            <th>Selesai</th>
                                            <th>Mata Kuliah</th>
                                            <th>Dosen</th>
                                            <th>Jumlah Mahasiswa</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- ./card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $renderer->endSection() ?>

<?= $renderer->section('custom-js') ?>
<script>
    var firstInit = true;
    $(function() {
        var table = $('#data-jadwal').DataTable({
            dom: 'lrtip',
            searching: true,
            responsive: true,
            pageLength: 10,
            processing: true,
            serverSide: true,
            order: [],
            ajax: function(data, callback, settings) {
                if (firstInit) {
                    var dayofweek = new Date().getDay();
                    data.search.value = dayofweek;
                    firstInit = false;
                }
                
                var data = {
                    ...data,
                    ['<?= csrf_token() ?>']: '<?= csrf_hash() ?>'
                }
                $.ajax({
                    type: 'post',
                    url: '<?= route_to('jadwal.get-data') ?>',
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
                {
                    targets: 7,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 8,
                    orderable: false,
                    searchable: false
                }
            ],
            drawCallback: function(settings) {}
        });

        $('ul.nav.nav-pills.jadwal-days').on('click', 'li.nav-item', function(e) {
            var dow = $(this).find('a').data().dow;
            table.search(dow).draw();
        });

        var dayofweek = new Date().getDay();
        $('ul.nav.nav-pills.jadwal-days li.nav-item').each(function(index, tab) {
            var dow = $(tab).find('a').data().dow;
            if (dow == dayofweek) {
                $(tab).find('a').addClass('active');
            }
        });
    });
</script>
<?= $renderer->endSection() ?>