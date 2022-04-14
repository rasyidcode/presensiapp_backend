<?php

// protected route
$routes->group('api/v1', ['namespace' => $routes_namespace], function ($routes) {
    $routes->get('/', 'Entry\Controllers\EntryController::index', []);

    $routes->group('auth', ['filter' => 'level-filter:prevent-admin'], function($routes) {
        // sign in
        $routes->post('login', 'Shared\Controllers\AuthController::login');
        // logout
        $routes->post('logout', 'Shared\Controllers\AuthController::logout');
        // renew access_token
        $routes->post('renew-token', 'Shared\Controllers\AuthController::renewToken');
        // forgot password
        $routes->post('forgot-password', 'Shared\Controllers\AuthController::forgotPassword');
    });

    /*********** mahasiswa routes */
    // need auth
    $routes->group('mahasiswa', ['filter' => 'level-auth-filter:only-mahasiswa'], function($routes) {
        // list perkuliahan hari ini
        $routes->get('perkuliahan', 'Mhs\Controllers\PerkuliahanController::list');
        // list presensi
        // $routes->get('perkuliahan/{matkul}/presensi', 'Mhs\Controllers\PerkuliahanController::listPresensi/$1');
        // do presensi
        // $routes->post('perkuliahan/{matkul}/presensi', 'Mhs\Controllers\PerkuliahanController::doPresensi/$1');
        // list matkul
        // $routes->get('matkul', 'Mhs\Controllers\MatkulController::index');
        // detail matkul
        // $routes->get('matkul/{name_slug}', 'Mhs\Controllers\MatkulController::get/$1');
        // profile
        // $routes->get('profile', 'Mhs\Controllers\ProfileController::index');
        // list jadwal matkul
        // $routes->get('jadwal', 'Mhs\Controllers\JadwalController::index');
    });

    /********** dosen routes */
    $routes->group('dosen', ['filter' => 'auth-dosen-filter'], function($routes) {

    });

    /********** admin routes */
    $routes->group('dosen', ['filter' => 'auth-admin-filter'], function($routes) {

    });
});
