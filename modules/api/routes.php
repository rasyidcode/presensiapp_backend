<?php

$routes->group('api/v1', ['namespace' => $routes_namespace], function ($routes) use ($routes_namespace) {
    $routes->get('/', 'Entry\Controllers\EntryController::index');

    // auth
    $routes->group('auth', [
        'namespace' => $routes_namespace . 'Auth\Controllers'
    ], function($routes) {
        $routes->post('/',              'AuthController::login'); // done
        $routes->post('renew-token',    'AuthController::renewToken'); // done
    });
    
    // auth protected
    $routes->group('auth', [
        'namespace' => $routes_namespace . 'Auth\Controllers', 
        'filter'    => 'api-auth-filter'
    ], function($routes) {
        $routes->post('logout', 'AuthController::logout'); // done  
    });

    // perkuliahan protected
    $routes->group('perkuliahan', [
        'namespace' => $routes_namespace . 'Perkuliahan\Controllers', 
        'filter'    => 'api-auth-filter'
    ], function($routes) {
        $routes->get('/',               'PerkuliahanController::index');
        $routes->get('(:segment)',      'PerkuliahanController::get/$1');
        $routes->post('do-presensi',    'PerkuliahanController::doPresensi');
    });

    // dosen protected
    $routes->group('dosen', [
        'namespace' => $routes_namespace . 'Dosen\Controllers',
        'filter'    => 'api-auth-filter'
    ], function($routes) {
        $routes->get('perkuliahan',                         'DosenController::listPerkuliahan');
        $routes->get('perkuliahan/(:segment)',              'DosenController::perkuliahan/$1');
        $routes->post('post-qr',                            'DosenController::postQr');
        $routes->get('perkuliahan/(:segment)/mahasiswa',    'DosenController::perkuliahanMahasiswa/$1');
    });
});
 