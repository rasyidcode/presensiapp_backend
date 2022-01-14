<?php

$routes->group('api/v1', ['namespace' => $routes_namespace], function($routes) {
    $routes->get('/', 'Entry\Controllers\EntryController::index');

    // auth
    $routes->group('auth', function($routes) {
        $routes->get('/', 'Auth\Controllers\AuthController::index');
        $routes->post('signIn', 'Auth\Controllers\AuthController::signIn');
        $routes->post('signOut', 'Auth\Controllers\AuthController::signOut');
    });

    // presensi
    $routes->group('presensi', function($routes) {
        // @note: only user dosen has access on this endpoint
        // @body:
        // {
        //     "presensi_secret": "random_string"
        // }
        $routes->post('makePresensi', 'Presensi\Controllers\PresensiController::makePresensi');

        // @note: - only user mhs has access on this endpoint
        //        - presensi hanya boleh dilakukan sekali untuk 1 presensi/pertemuan
        // @body
        // {
        //     "presensi_secret": "random_string",
        //     "id_jadwal": 0,
        // }
        $routes->post('doPresensi', 'Presensi\Controllers\PresensiController::doPresensi');
    });

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