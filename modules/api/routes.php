<?php

$routes->group('api/v1', ['namespace' => $routes_namespace], function($routes) {
    // auth
    $routes->post('auth/signIn', 'Auth\Controllers\AuthController::signIn');

    // protected route
    $routes->group('/', ['filter' => 'api_auth_filter'], function($routes) {
        $routes->get('/', 'Entry\Controllers\EntryController::index');
        // logout
        $routes->post('auth/signOut', 'Auth\Controllers\AuthController::signOut', ['filter' => 'api_logout_filter']);
    });
});