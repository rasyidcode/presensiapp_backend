<?php

namespace Modules\Admin\Dashboard\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
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
        return $this->renderView('v_404', [
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

    public function logout()
    {
        session()->destroy();
        
        return $this->response
            ->setJSON([
                'success' => true,
                'message' => 'User logged out!'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}