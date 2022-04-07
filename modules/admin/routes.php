<?php

$routes->get('login', $routes_namespace.'Login/Controllers/LoginController::index');

$routes->group('admin', ['namespace' => $routes_namespace, 'filter' => 'web_auth_filter'], function($routes) {
    // welcome page
    $routes->get('/', 'Dashboard/Controllers/DashboardController::index');

    // user management

    // logout
});