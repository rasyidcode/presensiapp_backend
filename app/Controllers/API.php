<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class API extends BaseController
{
    public function index()
    {
        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Test'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
