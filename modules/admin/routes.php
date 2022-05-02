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
    // $routes->get('user/change-pass',        'Users/Controllers/UserController::view_change_pass/$1',    ['as' => 'user.view_change_pass']);
    // $routes->post('user/change-pass',       'Users/Controllers/UserController::change_pass',            ['as' => 'user.change_pass']);
    // $routes->get('user/edit',               'Users/Controllers/UserController::view_edit/$1',           ['as' => 'user.view_edit']);
    // $routes->post('user/edit',              'Users/Controllers/UserController::edit',                   ['as' => 'user.edit']);
    // $routes->get('user/delete',             'Users/Controllers/UserController::view_delete/$1',         ['as' => 'user.view_delete']);
    // $routes->post('user/delete',            'Users/Controllers/UserController::delete',                 ['as' => 'user.delete']);
    // $routes->post('user/get-data-history',  'Users/Controllers/UserController::get_data_history',       ['as' => 'user.get_data_history']);
    // $routes->get('user/restore',            'Users/Controllers/UserController::view_restore',           ['as' => 'user.view_restore']);
    // $routes->post('user/restore',           'Users/Controllers/UserController::restore',                ['as' => 'user.restore']);
    // $routes->get('user/purge',              'Users/Controllers/UserController::view_purge',             ['as' => 'user.view_purge']);
    // $routes->post('user/purge',             'Users/Controllers/UserController::purge',                  ['as' => 'user.purge']);

    // master data
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
    $routes->get('dosen',           'Dosen/Controllers/DosenController::index',     ['as' => 'dosen.list']);
    $routes->post('dosen/get-data',  'Dosen/Controllers/DosenController::getData',   ['as' => 'dosen.get-data']);
    $routes->get('dosen/add',       'Dosen/Controllers/DosenController::add',       ['as' => 'dosen.add']);
    $routes->get('dosen',           'Dosen/Controllers/DosenController::create',    ['as' => 'dosen.create']);

    // data kelas
    $routes->get('kelas', 'Kelas/Controllers/KelasController::index', ['as' => 'kelas.list']);
    $routes->get('kelas/get-data', 'Kelas/Controllers/KelasController::getData', ['as' => 'kelas.get-data']);

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
