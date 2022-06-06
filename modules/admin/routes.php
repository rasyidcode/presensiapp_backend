<?php

$routes->group('', ['namespace' => $routes_namespace, 'filter' => 'web-auth-filter'], function ($routes) use ($routes_namespace) {
    // welcome page
    $routes->get('/', 'Dashboard/Controllers/DashboardController::index',               ['as' => 'admin.welcome']);
    // error page
    $routes->get('error-404', 'Dashboard/Controllers/DashboardController::error404',    ['as' => 'admin.error-404']);
    // logout
    $routes->post('logout', 'Dashboard/Controllers/DashboardController::logout',        ['as' => 'logout']);

    // data user
    $routes->group('user', ['namespace' => $routes_namespace . 'Users\Controllers\\'], function($routes) {
        $routes->get('/',            'UserController::index',      ['as' => 'user.list']);
        $routes->post('get-data',   'UserController::getData',    ['as' => 'user.get-data']);
        $routes->get('add',         'UserController::add',        ['as' => 'user.add']);
        $routes->post('delete',     'UserController::create',     ['as' => 'user.create']);
    });

    // data master
    $routes->group('master', ['namespace' => $routes_namespace . 'Master\Controllers\\'], function($routes) {
        // jurusan
        $routes->get('jurusan',             'JurusanController::index',     ['as' => 'master.jurusan.list']);
        $routes->post('jurusan/get-data',   'JurusanController::getData',   ['as' => 'master.jurusan.get-data']);
        $routes->get('jurusan/add',         'JurusanController::add',       ['as' => 'master.jurusan.add']);
        $routes->post('jurusan',            'JurusanController::create',    ['as' => 'master.jurusan.create']);

        // matkul
        $routes->get('matkul',               'MatkulController::index',     ['as' => 'master.matkul.list']);
        $routes->post('matkul/get-data',     'MatkulController::getData',   ['as' => 'master.matkul.get-data']);
        $routes->get('matkul/add',           'MatkulController::add',       ['as' => 'master.matkul.add']);
        $routes->post('matkul',              'MatkulController::create',    ['as' => 'master.matkul.create']);
    });

    // data mahasiswa
    $routes->group('mahasiswa', ['namespace' => $routes_namespace . 'Mahasiswa\Controllers\\'], function($routes) {
        $routes->get('/',           'MahasiswaController::index',     ['as' => 'mahasiswa.list']);
        $routes->post('get-data',   'MahasiswaController::getData',   ['as' => 'mahasiswa.get-data']);
        $routes->get('add',         'MahasiswaController::add',       ['as' => 'mahasiswa.add']);
        $routes->post('/',          'MahasiswaController::create',    ['as' => 'mahasiswa.create']);
    });

    // data dosen
    $routes->group('dosen', ['namespace' => $routes_namespace . 'Dosen\Controllers\\'], function($routes) {
        $routes->get('/',           'DosenController::index',     ['as' => 'dosen.list']);
        $routes->post('get-data',   'DosenController::getData',   ['as' => 'dosen.get-data']);
        $routes->get('add',         'DosenController::add',       ['as' => 'dosen.add']);
        $routes->post('/',          'DosenController::create',    ['as' => 'dosen.create']);
    });

    // data kelas
    $routes->group('kelas', ['namespace' => $routes_namespace . 'Kelas\Controllers'], function ($routes) {
        $routes->get('/',                               'KelasController::index',                 ['as' => 'kelas.list']);
        $routes->post('get-data',                       'KelasController::getData',               ['as' => 'kelas.get-data']);
        $routes->get('add',                             'KelasController::add',                   ['as' => 'kelas.add']);
        $routes->post('/',                              'KelasController::create',                ['as' => 'kelas.create']);
        $routes->get('(:segment)/mahasiswa',            'KelasController::mahasiswa/$1',          ['as' => 'kelas.mahasiswa']);
        $routes->post('(:segment)/mahasiswa/get-data',  'KelasController::mahasiswaGetData/$1',   ['as' => 'kelas.mahasiswa.get-data']);
        $routes->get('(:segment)/mahasiswa/add',        'KelasController::mahasiswaAdd/$1',       ['as' => 'kelas.mahasiswa.add']);
        $routes->post('(:segment)/mahasiswa',           'KelasController::mahasiswaCreate/$1',    ['as' => 'kelas.mahasiswa.create']);
    });

    // data jadwal
    $routes->group('jadwal', ['namespace' => $routes_namespace . 'Jadwal\Controllers\\'], function($routes) {
        $routes->get('/',           'JadwalController::index',       ['as' => 'jadwal.list']);
        $routes->post('get-data',   'JadwalController::getData',     ['as' => 'jadwal.get-data']);
        $routes->get('add',         'JadwalController::add',         ['as' => 'jadwal.add']);
        $routes->post('/',          'JadwalController::create',      ['as' => 'jadwal.create']);
    });

    // data presensi
    $routes->group('presensi', ['namespace' => $routes_namespace . 'Presensi\Controllers\\'], function($routes) {
        $routes->get('/',           'PresensiController::index',   ['as' => 'presensi.list']);
        $routes->post('get-data',   'PresensiController::getData', ['as' => 'presensi.get-data']);
    });
});
