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

    // user management

    // logout
    $routes->post('logout', 'Login/Controllers/LoginController::logout', ['as' => 'logout']);
});
