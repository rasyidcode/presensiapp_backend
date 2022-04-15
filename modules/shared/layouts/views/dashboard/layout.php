<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Presensi App Admin</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= site_url('adminlte3/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= site_url('adminlte3/dist/css/adminlte.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?=site_url('adminlte3/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?=site_url('adminlte3/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?=site_url('adminlte3/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')?>">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a id="btn-logout" href="javascript:void(0)" class="nav-link">
                        Logout <i class="fas fa-sign-out-alt ml-2"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?= $this->include('\Modules\Shared\Layouts\Views\Dashboard\inc_sidebar') ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $page_title ?></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <?php foreach($pageLinks as $pageKey => $pageVal): ?>
                                    <?php
                                        $pagesKey = explode('-', $pageKey);
                                        $pageKey = count($pagesKey) > 1 ? implode(' ', $pagesKey) : $pageKey;
                                    ?>
                                    <li class="breadcrumb-item <?=$pageVal['active'] ? 'active' : ''?>">
                                        <?php if (!$pageVal['active']): ?>
                                            <a href="<?=$pageVal['url']?>"><?=ucwords($pageKey)?></a>
                                        <?php else: ?>
                                            <?=ucwords($pageKey)?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <?= $this->renderSection('content') ?>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Anything you want
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="<?= site_url('adminlte3/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= site_url('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?= site_url('adminlte3/dist/js/adminlte.min.js') ?>"></script>
    <!-- Datatable -->
    <?=$this->include('\Modules\Shared\Layouts\Views\Dashboard\inc_datatables_js')?>
    <!-- Handle logout -->
    <script>
        $(function() {
            $('#btn-logout').click(function(e) {
                $.ajax({
                    url: '<?= route_to('logout') ?>',
                    type: 'POST',
                    data: {
                        ['<?= csrf_token() ?>']: '<?= csrf_hash() ?>'
                    },
                    success: function(res) {
                        console.log(res);
                        location.reload();
                    },
                    error: function(err) {
                        console.log(err);
                        alert('Logout failed!');
                    }
                })
            });
        });
    </script>

    <!-- Custom JS -->
    <?=$this->renderSection('custom-js')?>
</body>

</html>