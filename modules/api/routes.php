<?php

$routes->group('api/v1', ['namespace' => $routes_namespace], function($routes) {
    $routes->get('/', 'Default\Controllers\DefaultController::index');

    // auth
    $routes->group('auth', function($routes) {
        $routes->get('signIn', 'Auth\Controllers\AuthController::signIn');
        $routes->get('signOut', 'Auth\Controllers\AuthController::signOut');
    });

    /**
     * 
     */

    // dosen
    $routes->group('dosen', function($routes) {
        $routes->group('perkuliahan', function($routes) {
            $routes->get('/', 'Dosen\Controllers\PerkuliahanController::index');
            /**
             * res body:
             * {
             *      "success": true,
             *      "data": {
             *          "mahasiswa": [],
             *          "matkul": ""
             *      }
             * }
             */
            $routes->post('genereatePresensi', 'Dosen\Controllers\PerkuliahanController::genereatePresensi');
            /**
             * req body:
             * {
             *      
             * }
             */
        });
        // list jadwal mengajar
        // data presensi
        // perkuliahan yang akan datang
        /**
         * -> list mhs
         * -> matkul
         * -> 
         * 
         */

        // generate presensi
    });

    // mahasiswa
    $routes->group('mhs', function($routes) {
        // jadwal perkuliahan
        // perkuliahan yang akan datang
    });

    // users

    // presensi

    // matkul

    // kelas

    // perkuliahan
});