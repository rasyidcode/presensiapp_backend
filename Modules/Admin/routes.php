<?php

$routes->group('', ['namespace' => $routes_namespace, 'filter' => 'web-auth-filter'], function ($routes) use ($routes_namespace) {
    // welcome page
    $routes->get('/', 'Dashboard/Controllers/DashboardController::index',               ['as' => 'admin.welcome']);
    // error page
    $routes->get('error-404', 'Dashboard/Controllers/DashboardController::error404',    ['as' => 'admin.error-404']);
    // logout
    $routes->post('logout', 'Dashboard/Controllers/DashboardController::logout',        ['as' => 'logout']);

    // data user
    $routes->group('user', [
        'namespace' => $routes_namespace . 'Users\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',                       'UserController::index',            ['as' => 'user.list']);
        $routes->post('get-data',               'UserController::getData',          ['as' => 'user.get-data']);
        $routes->get('add',                     'UserController::add',              ['as' => 'user.add']);
        $routes->post('create',                 'UserController::create',           ['as' => 'user.create']);
        $routes->get('(:segment)/edit',         'UserController::edit/$1',          ['as' => 'user.edit']);
        $routes->post('(:segment)/update',      'UserController::update/$1',        ['as' => 'user.update']);
        $routes->get('(:segment)/change-pass',  'UserController::changePass/$1',    ['as' => 'user.change-pass']);
        $routes->post('(:segment)/change-pass', 'UserController::doChangePass/$1',  ['as' => 'user.do-change-pass']);
        $routes->post('(:segment)/delete',      'UserController::delete/$1',        ['as' => 'user.delete']);
    });

    // data master
    $routes->group('master', [
        'namespace' => $routes_namespace . 'Master\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        // jurusan
        $routes->get('jurusan',                     'JurusanController::index',         ['as' => 'master.jurusan.list']);
        $routes->post('jurusan/get-data',           'JurusanController::getData',       ['as' => 'master.jurusan.get-data']);
        $routes->get('jurusan/add',                 'JurusanController::add',           ['as' => 'master.jurusan.add']);
        $routes->post('jurusan',                    'JurusanController::create',        ['as' => 'master.jurusan.create']);
        $routes->get('jurusan/(:segment)/edit',     'JurusanController::edit/$1',       ['as' => 'master.jurusan.edit']);
        $routes->post('jurusan/(:segment)/update',  'JurusanController::update/$1',     ['as' => 'master.jurusan.update']);
        $routes->post('jurusan/(:segment)/delete',  'JurusanController::delete/$1',     ['as' => 'master.jurusan.delete']);

        // matkul
        $routes->get('matkul',                      'MatkulController::index',      ['as' => 'master.matkul.list']);
        $routes->post('matkul/get-data',            'MatkulController::getData',    ['as' => 'master.matkul.get-data']);
        $routes->get('matkul/add',                  'MatkulController::add',        ['as' => 'master.matkul.add']);
        $routes->post('matkul',                     'MatkulController::create',     ['as' => 'master.matkul.create']);
        $routes->get('matkul/(:segment)/edit',      'MatkulController::edit/$1',    ['as' => 'master.matkul.edit']);
        $routes->post('matkul/(:segment)/update',   'MatkulController::update/$1',  ['as' => 'master.matkul.update']);
        $routes->post('matkul/(:segment)/delete',    'MatkulController::delete/$1', ['as' => 'master.matkul.delete']);
    });

    // data mahasiswa
    $routes->group('mahasiswa', [
        'namespace' => $routes_namespace . 'Mahasiswa\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',                   'MahasiswaController::index',       ['as' => 'mahasiswa.list']);
        $routes->post('get-data',           'MahasiswaController::getData',     ['as' => 'mahasiswa.get-data']);
        $routes->get('add',                 'MahasiswaController::add',         ['as' => 'mahasiswa.add']);
        $routes->post('/',                  'MahasiswaController::create',      ['as' => 'mahasiswa.create']);
        $routes->get('(:segment)/edit',     'MahasiswaController::edit/$1',     ['as' => 'mahasiswa.edit']);
        $routes->post('(:segment)/update',  'MahasiswaController::update/$1',   ['as' => 'mahasiswa.update']);
        $routes->post('(:segment)/delete',  'MahasiswaController::delete/$1',   ['as' => 'mahasiswa.delete']);
    });

    // data dosen
    $routes->group('dosen', [
        'namespace' => $routes_namespace . 'Dosen\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',                   'DosenController::index',       ['as' => 'dosen.list']);
        $routes->post('get-data',           'DosenController::getData',     ['as' => 'dosen.get-data']);
        $routes->get('add',                 'DosenController::add',         ['as' => 'dosen.add']);
        $routes->post('/',                  'DosenController::create',      ['as' => 'dosen.create']);
        $routes->get('(:segment)/edit',     'DosenController::edit/$1',     ['as' => 'dosen.edit']);
        $routes->post('(:segment)/update',  'DosenController::update/$1',   ['as' => 'dosen.update']);
        $routes->post('(:segment)/delete',  'DosenController::delete/$1',   ['as' => 'dosen.delete']);
    });

    // data kelas
    $routes->group('kelas', [
        'namespace' => $routes_namespace . 'Kelas\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',                                       'KelasController::index',                   ['as' => 'kelas.list']);
        $routes->post('get-data',                               'KelasController::getData',                 ['as' => 'kelas.get-data']);
        $routes->get('add',                                     'KelasController::add',                     ['as' => 'kelas.add']);
        $routes->post('/',                                      'KelasController::create',                  ['as' => 'kelas.create']);
        $routes->get('(:segment)/edit',                         'KelasController::edit/$1',                 ['as' => 'kelas.edit']);
        $routes->post('(:segment)/update',                      'KelasController::update/$1',               ['as' => 'kelas.update']);
        $routes->post('(:segment)/delete',                      'KelasController::delete/$1',               ['as' => 'kelas.delete']);

        $routes->get('(:segment)/mahasiswa',                    'KelasController::mahasiswa/$1',            ['as' => 'kelas.mahasiswa']);
        $routes->post('(:segment)/mahasiswa/get-data',          'KelasController::mahasiswaGetData/$1',     ['as' => 'kelas.mahasiswa.get-data']);
        $routes->get('(:segment)/mahasiswa/add',                'KelasController::mahasiswaAdd/$1',         ['as' => 'kelas.mahasiswa.add']);
        $routes->post('(:segment)/mahasiswa',                   'KelasController::mahasiswaCreate/$1',      ['as' => 'kelas.mahasiswa.create']);
        $routes->post('(:segment)/mahasiswa/(:segment)/delete', 'KelasController::mahasiswaDelete/$1/$2',   ['as' => 'kelas.mahasiswa.delete']);
    });

    // data jadwal
    $routes->group('jadwal', [
        'namespace' => $routes_namespace . 'Jadwal\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',                   'JadwalController::index',          ['as' => 'jadwal.list']);
        $routes->post('get-data',           'JadwalController::getData',        ['as' => 'jadwal.get-data']);
        $routes->get('add',                 'JadwalController::add',            ['as' => 'jadwal.add']);
        $routes->post('/',                  'JadwalController::create',         ['as' => 'jadwal.create']);
        $routes->get('(:segment)/edit',     'JadwalController::edit/$1',        ['as' => 'jadwal.edit']);
        $routes->post('(:segment)/update',  'JadwalController::update/$1',      ['as' => 'jadwal.update']);
        $routes->post('(:segment)/delete',  'JadwalController::delete/$1',      ['as' => 'jadwal.delete']);
    });

    // data presensi
    $routes->group('presensi', [
        'namespace' => $routes_namespace . 'Presensi\Controllers\\',
        'filter' => 'web-auth-filter'
    ], function ($routes) {
        $routes->get('/',           'PresensiController::index',   ['as' => 'presensi.list']);
        $routes->post('get-data',   'PresensiController::getData', ['as' => 'presensi.get-data']);
    });
});
