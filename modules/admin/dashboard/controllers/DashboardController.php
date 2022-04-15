<?php

namespace Modules\Admin\Dashboard\Controllers;

class DashboardController extends \App\Controllers\BaseController
{
    public function index()
    {
        return view('\Modules\Admin\Dashboard\Views\v_welcome', [
            'page_title'    => 'Welcome',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function error404()
    {
        return view('\Modules\Shared\Errors\Views\404', [
            'page_title'    => 'Not Found',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'not-found'     => [
                    'url'       => route_to('admin.error-404'),
                    'active'    => true,
                ],
            ]
        ]);
    }
}