<?php

$routes->get('login', $routes_namespace . 'Login/Controllers/LoginController::index', [
        'filter'    => 'web_redirect_if_auth_filter',
        'as'        => 'login'
    ]
);
$routes->post('login', $routes_namespace . 'Login/Controllers/LoginController::login');

$routes->group('admin', ['namespace' => $routes_namespace, 'filter' => 'web_auth_filter'], function ($routes) {
    // welcome page
    $routes->get('/', 'Dashboard/Controllers/DashboardController::index',               ['as' => 'admin.welcome']);
    $routes->get('error-404', 'Dashboard/Controllers/DashboardController::error404',    ['as' => 'admin.error-404']);

    // data user
    $routes->get('user',            'Users/Controllers/UserController::index',      ['as' => 'user.list']);
    $routes->post('user/get-data',  'Users/Controllers/UserController::getData',    ['as' => 'user.get-data']);
    $routes->get('user/add',        'Users/Controllers/UserController::add',        ['as' => 'user.add']);
    $routes->post('user',           'Users/Controllers/UserController::create',     ['as' => 'user.create']);

    // jurusan
    $routes->get('master/jurusan',              'Master/Controllers/JurusanController::index',          ['as' => 'master.jurusan.list']);
    $routes->post('master/jurusan/get-data',    'Master/Controllers/JurusanController::getData',        ['as' => 'master.jurusan.get-data']);
    $routes->get('master/jurusan/add',          'Master/Controllers/JurusanController::add',            ['as' => 'master.jurusan.add']);
    $routes->post('master/jurusan',             'Master/Controllers/JurusanController::create',         ['as' => 'master.jurusan.create']);

    // matkul
    $routes->get('master/matkul',               'Master/Controllers/MatkulController::index',           ['as' => 'master.matkul.list']);
    $routes->post('master/matkul/get-data',     'Master/Controllers/MatkulController::getData',         ['as' => 'master.matkul.get-data']);
    $routes->get('master/matkul/add',           'Master/Controllers/MatkulController::add',             ['as' => 'master.matkul.add']);
    $routes->post('master/matkul',              'Master/Controllers/MatkulController::create',          ['as' => 'master.matkul.create']);
    
    // data mahasiswa
    $routes->get('mahasiswa',                   'Mahasiswa/Controllers/MahasiswaController::index',     ['as' => 'mahasiswa.list']);
    $routes->post('mahasiswa/get-data',         'Mahasiswa/Controllers/MahasiswaController::getData',   ['as' => 'mahasiswa.get-data']);
    $routes->get('mahasiswa/add',               'Mahasiswa/Controllers/MahasiswaController::add',       ['as' => 'mahasiswa.add']);
    $routes->post('mahasiswa',                  'Mahasiswa/Controllers/MahasiswaController::create',    ['as' => 'mahasiswa.create']);

    // data dosen
    $routes->get('dosen',               'Dosen/Controllers/DosenController::index',     ['as' => 'dosen.list']);
    $routes->post('dosen/get-data',     'Dosen/Controllers/DosenController::getData',   ['as' => 'dosen.get-data']);
    $routes->get('dosen/add',           'Dosen/Controllers/DosenController::add',       ['as' => 'dosen.add']);
    $routes->post('dosen',              'Dosen/Controllers/DosenController::create',    ['as' => 'dosen.create']);

    // data kelas
    $routes->get('kelas',                       'Kelas/Controllers/KelasController::index',             ['as' => 'kelas.list']);
    $routes->post('kelas/get-data',             'Kelas/Controllers/KelasController::getData',           ['as' => 'kelas.get-data']);
    $routes->get('kelas/add',                   'Kelas/Controllers/KelasController::add',               ['as' => 'kelas.add']);
    $routes->post('kelas',                      'Kelas/Controllers/KelasController::create',            ['as' => 'kelas.create']);

    $routes->get('kelas/(:segment)/mahasiswa',             'Kelas\Controllers\KelasController::mahasiswa/$1',         ['as' => 'kelas.mahasiswa']);
    $routes->post('kelas/(:segment)/mahasiswa/get-data',   'Kelas\Controllers\KelasController::mahasiswaGetData/$1',  ['as' => 'kelas.mahasiswa.get-data']);
    // $routes->get('kelas/mahasiswa/add',         'Kelas/Controllers/KelasController::mahasiswaAdd',      ['as' => 'kelas.mahasiswa.add']);

    // jadwal
    $routes->get('jadwal', 'Jadwal/Controllers/JadwalController::index', ['as' => 'jadwal.list']);
    $routes->get('jadwal/get-data', 'Kelas/Controllers/JadwalController::getData', ['as' => 'jadwal.get-data']);

    // presensi
    $routes->get('presensi', 'Presensi/Controllers/PresensiController::index', ['as' => 'presensi.list']);
    $routes->get('presensi/get-data', 'Presensi/Controllers/PresensiController::getData', ['as' => 'presensi.get-data']);

    // settings
    $routes->get('setting', 'Setting/Controllers/SettingController::index', ['as' => 'setting']);

    // logout
    $routes->post('logout', 'Login/Controllers/LoginController::logout', ['as' => 'logout']);
});
