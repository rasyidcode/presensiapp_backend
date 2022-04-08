<?php

$routes->get('login', $routes_namespace . 'Login/Controllers/LoginController::index', [
        'filter'    => 'web_redirect_if_auth_filter',
        'as'        => 'login'
    ]
);
$routes->post('login', $routes_namespace . 'Login/Controllers/LoginController::login');

$routes->group('admin', ['namespace' => $routes_namespace, 'filter' => 'web_auth_filter'], function ($routes) {
    // welcome page
    $routes->get('/', 'Dashboard/Controllers/DashboardController::index');

    // data user
    $routes->get('user',                    'Users/Controllers/UserController::view_list',              ['as' => 'user.view_list']);
    $routes->post('user/get-data',          'User/Controllers/UserController::get_data',                ['as' => 'user.get_data']);
    $routes->get('user/change-pass',        'Users/Controllers/UserController::view_change_pass/$1',    ['as' => 'user.view_change_pass']);
    $routes->post('user/change-pass',       'Users/Controllers/UserController::change_pass',            ['as' => 'user.change_pass']);
    $routes->get('user/edit',               'Users/Controllers/UserController::view_edit/$1',           ['as' => 'user.view_edit']);
    $routes->post('user/edit',              'Users/Controllers/UserController::edit',                   ['as' => 'user.edit']);
    $routes->get('user/delete',             'Users/Controllers/UserController::view_delete/$1',         ['as' => 'user.view_delete']);
    $routes->post('user/delete',            'Users/Controllers/UserController::delete',                 ['as' => 'user.delete']);
    $routes->post('user/get-data-history',  'Users/Controllers/UserController::get_data_history',       ['as' => 'user.get_data_history']);
    $routes->get('user/restore',            'Users/Controllers/UserController::view_restore',           ['as' => 'user.view_restore']);
    $routes->post('user/restore',           'Users/Controllers/UserController::restore',                ['as' => 'user.restore']);
    $routes->get('user/purge',              'Users/Controllers/UserController::view_purge',             ['as' => 'user.view_purge']);
    $routes->post('user/purge',             'Users/Controllers/UserController::purge',                  ['as' => 'user.purge']);

    // master data

    // data mahasiswa

    // data dosen

    // data kelas

    // jadwal

    // perkuliahan

    // settings

    // logout
    $routes->post('logout', 'Login/Controllers/LoginController::logout', ['as' => 'logout']);
});
