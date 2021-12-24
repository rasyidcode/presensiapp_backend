<?php

namespace Modules\Api\Auth\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends \App\Controllers\BaseController
{
    public function index()
    {
        return $this->response
			->setJSON([
				'status'    => ResponseInterface::HTTP_OK,
				'message'   => 'Hello auth',
			])
			->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function signIn()
    {
        echo 'hello world';
    }
}