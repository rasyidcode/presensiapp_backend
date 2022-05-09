<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= site_url('adminlte3/dist/img/AdminLTELogo.png') ?>" alt="Presensi App Admin" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Presensi App Admin </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item <?= isMenuOpen('master', 2) ?>">
                    <a href="#" class="nav-link <?= isLinkActive('master', 2) ?>">
                        <i class="nav-icon fas fa-database" style="color: <?= isLinkActiveColor('master', 2, 'red') ?>"></i>
                        <p>
                            Master Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= route_to('master.jurusan.list') ?>" class="nav-link <?= isLinkActive('jurusan', 3) ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jurusan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= route_to('master.matkul.list') ?>" class="nav-link <?= isLinkActive('matkul', 3) ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mata Kuliah</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= route_to('user.list') ?>" class="nav-link <?= isLinkActive('user', 2) ?>">
                        <i class="nav-icon fas fa-user" style="color: <?= isLinkActiveColor('user', 2, 'green') ?>"></i>
                        <p>Data User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= route_to('mahasiswa.list') ?>" class="nav-link <?= isLinkActive('mahasiswa', 2) ?>">
                        <i class="nav-icon fas fa-user" style="color: <?= isLinkActiveColor('mahasiswa', 2, 'yellow') ?>"></i>
                        <p>Data Mahasiswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= route_to('dosen.list') ?>" class="nav-link <?= isLinkActive('dosen', 2) ?>">
                        <i class="nav-icon fas fa-user" style="color: <?= isLinkActiveColor('dosen', 2, 'blue') ?>"></i>
                        <p>Data Dosen</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= route_to('kelas.list') ?>" class="nav-link <?= isLinkActive('kelas', 2) ?>"">
                        <i class="nav-icon fas fa-door-closed" style="color: <?= isLinkActiveColor('kelas', 2, 'orange') ?>"></i>
                        <p>Data Kelas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-calendar-alt" style="color: <?= isLinkActiveColor('jadwal', 2, 'cyan') ?>"></i>
                        <p>Jadwal Perkuliahan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-qrcode" style="color: <?= isLinkActiveColor('presensi', 2, 'darkkhaki') ?>"></i>
                        <p>Data Presensi</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog" style="color: <?= isLinkActiveColor('setting', 2, 'gray') ?>"></i>
                        <p>Setting</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>