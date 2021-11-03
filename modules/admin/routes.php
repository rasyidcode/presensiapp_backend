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

    /** logout */
});

// DB Design
/** mahasiswa */
/**
 * properties : 
 * (id, nama)
 */

/** dosen */
/**
 * properties :
 * (id, nama)
 */

/** periode */
/**
* properties :
* (id, semester, year, start_at, end_at, created_at, updated_at, deleted_at)
*/

/** kelas */
/****
 * properties :
 * (id, id_dosen, id_matkul, id_periode, created_at, updated_at, deleted_at)
 */

/** jadwal */
/***
 * properties : 
 * (id, id_kelas, date, begin_time, end_time, created_at, updated_at, deleted_at)
 */

/** presensi */
/**
 * properties : 
 * (id, id_jadwal, secret, expired_at, created_at)
 */

/** kelas_mhs */
/***
 * properties :
 * (id, id_kelas, id_mhs)
 */

/** perkuliahan */
/*** properties : 
 * (id, id_presensi, id_mhs, status_presensi, created_at)
 */

// unused
// /** kelas_pertemuan */
// /****
//  * properties :
//  * (id, id_kelas, id_pertemuan)
//  */