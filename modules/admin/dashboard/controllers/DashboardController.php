<?php

namespace Modules\Admin\Dashboard\Controllers;

use Modules\Shared\Core\Controllers\BaseWebController;

class DashboardController extends BaseWebController
{

    protected $viewPath = __DIR__;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->renderView('v_welcome', [
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
        return $this->renderView('modules/shared/errors/views/404', [
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