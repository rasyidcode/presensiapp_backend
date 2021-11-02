<?php

namespace Modules\Api\Default\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class DefaultController extends \App\Controllers\BaseController
{
    public function index()
    {
        return $this->response
            ->setJSON([
                'status'    => ResponseInterface::HTTP_OK,
                'message'   => 'Welcome to presensiapp api!',
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}