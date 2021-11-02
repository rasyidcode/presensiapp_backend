<?php

$routes->group('dashboard/admin', ['namespace' => $routes_namespace], function($routes) {
    /** welcome */
    $routes->get('/', 'Welcome/Controllers/WelcomeController::index');

    /** dashboard */
    $routes->group('dashboard', 'Dashboard/Controllers/DashboardController::index');

    /** manage mhs */
    $routes->group('manage_mhs', function($routes) {
        $routes->get('/', 'ManageMhs/Controllers/ManageMhsController::index');
    });

    /** manage dosen */
    $routes->group('manage_dosen', function($routes) {
        $routes->get('/', 'ManageDosen/Controllers/ManageDosenController::index');
    });

    /** manage matkul */
    $routes->group('manage_matkul', function($routes) {
        /**
         * properties :
         * (id, nama, created_at, updated_at, deleted_at)
         * actions :
         * -> list matkul
         * -> add matkul
         * -> edit matkul
         * -> delete matkul
         * -> detail matkul
         */
    });

    /** manage jurusan */

    /** kelas */
    /****
     * properties :
     * (id, id_dosen, id_matkul, jumlah_pertemuan, presensi_secret, semester, created_at, updated_at, deleted_at)
     */

    /** kelas_pertemuan */
    /****
     * properties :
     * (id, id_kelas, id_pertemuan)
     */

    /** pertemuan */
    /***
     * properties : 
     * (id, day, begin_time, end_time, created_at, updated_at, deleted_at)
     */

    /** mhs_kelas */
    /***
     * properties :
     * (id, id_kelas, id_mhs)
     */

    /** perkuliahan */
    /*** properties : 
     * (id, id_kelas_pertemuan, id_mhs, pertemuan_ke, status_presensi, created_at, updated_at, deleted_at)
     */

    /** logout */
});