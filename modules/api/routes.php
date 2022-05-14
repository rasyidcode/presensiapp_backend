<?php

// protected route
$routes->group('api/v1', ['namespace' => $routes_namespace], function ($routes) use ($routes_namespace) {
    // entry
    $routes->get('/', 'Entry\Controllers\EntryController::index', []);
    // login
    $routes->post('auth/login', 'Auth\Controllers\AuthController::login'); // done
    // renew access_token
    $routes->post('auth/renew-token', 'Auth\Controllers\AuthController::renewToken');
    
    // need auth
    $routes->group('', ['namespace' => $routes_namespace, 'filter' => 'api-auth-filter'], function ($routes) {
        // logout
        $routes->post('auth/logout', 'Auth\Controllers\AuthController::logout'); // done
        // list perkuliahan hari ini
        $routes->get('perkuliahan', 'Perkuliahan\Controllers\PerkuliahanController::index');        
        // get perkuliahan by id
        $routes->get('perkuliahan/(:segment)', 'Perkuliahan\Controllers\PerkuliahanController::get/$1');
        // do presensi
        $routes->post('perkuliahan/do-presensi', 'Perkuliahan\Controllers\PerkuliahanController::doPresensi');
        // forgot password
        // $routes->post('auth/forgot-password', 'Shared\Controllers\AuthController::forgotPassword');

    });

    // need auth
    $routes->group('', ['filter' => 'api-auth-filter'], function ($routes) {
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

    $routes->group('dosen', ['filter' => 'auth-dosen-filter'], function ($routes) {
    });

    $routes->group('dosen', ['filter' => 'auth-admin-filter'], function ($routes) {
    });
});
