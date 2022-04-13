<?php

// protected route
$routes->group('api/v1', ['namespace' => $routes_namespace], function ($routes) {
    $routes->get('/', 'Entry\Controllers\EntryController::index', []);

    /*********** mahasiswa routes */
    // sign in
    $routes->post('mhs/auth/login', 'Mhs\Controllers\LoginController::login', ['filter' => 'mhs-only']);
    // need auth
    $routes->group('mhs', ['filter' => 'mhs-only:auth'], function($routes) {
        // logout
        $routes->post('auth/logout', 'Mhs\Controllers\LoginController::logout');
        // renew access_token
        $routes->post('auth/renew-token', 'Mhs\Controllers\LoginController::renewToken');
        // forgot password
        $routes->post('auth/forgot-password', 'Mhs\Controllers\LoginController::forgotPassword');
        // list perkuliahan hari ini
        $routes->get('perkuliahan', 'Mhs\Controllers\PerkuliahanController::list');
        // list presensi
        $routes->get('perkuliahan/{matkul}/presensi', 'Mhs\Controllers\PerkuliahanController::listPresensi/$1');
        // do presensi
        $routes->post('perkuliahan/{matkul}/presensi', 'Mhs\Controllers\PerkuliahanController::doPresensi/$1');
        // list matkul
        $routes->get('matkul', 'Mhs\Controllers\MatkulController::index');
        // detail matkul
        $routes->get('matkul/{name_slug}', 'Mhs\Controllers\MatkulController::get/$1');
        // profile
        $routes->get('profile', 'Mhs\Controllers\ProfileController::index');
        // list jadwal matkul
        $routes->get('jadwal', 'Mhs\Controllers\JadwalController::index');
    });

    /********** dosen routes */
    $routes->group('dosen', ['filter' => 'dosen_only'], function($routes) {

    });
});
