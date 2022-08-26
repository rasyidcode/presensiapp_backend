<?= $renderer->extend('Modules/Shared/Layouts/Views/Dashboard/layout') ?>

<?= $renderer->section('content') ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Hello, Admin!</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Mahasiswa</span>
                        <span class="info-box-number total-mhs"><?= $totalMahasiswa ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Dosen</span>
                        <span class="info-box-number total-dosen"><?= $totalDosen ?></span>
                    </div>
                </div>
            </div>

            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-briefcase"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Perkuliahan</span>
                        <span class="info-box-number total-jadwal"><?= $totalPerkuliahan ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-info"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Mata Kuliah</span>
                        <span class="info-box-number total-matkul"><?= $totalMatkul ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Recent Logs</h3>
                    </div>

                    <div class="card-body">
                        <div class="timeline">

                            <?php foreach ($recentLogs as $recentLogItem) : ?>
                                <div>
                                    <i class="fas fa-info bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> <?= $recentLogItem->created_at ?></span>
                                        <div class="timeline-body">
                                            <?= $recentLogItem->body ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perkuliahan Akan Datang</h3>
                    </div>

                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php foreach ($perkuliahan as $perkuliahanItem) : ?>
                                <li class="item">
                                    <div class="product-info ml-2">
                                        <a href="javascript:void(0)" class="product-title"><?= $perkuliahanItem->matkul ?>
                                            <span class="badge badge-success float-right"><?= $perkuliahanItem->begin_time ?> - <?= $perkuliahanItem->end_time ?></span></a>
                                        <span class="product-description">
                                            <?= $perkuliahanItem->dosen ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<?= $renderer->endSection() ?>