<?php

namespace Modules\Api\Auth\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Auth\Models\AuthModel;

class AuthController extends \App\Controllers\BaseController
{

    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

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
        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        
        if (!$this->validate($rules)) {
            $message        = 'Validation error.';
            $errors         = $this->validator->getErrors();
            $statusCode     = ResponseInterface::HTTP_BAD_REQUEST;

            return $this->response
                ->setJSON([
                    'success'   => false,
                    'status'    => $statusCode,
                    'message'   => $message,
                    'errors'    => $errors
                ])
                ->setStatusCode($statusCode);
        }

        $username   = $this->request->getVar('username');
        $password   = $this->request->getVar('password');

        $userdata = $this->authModel->getUser($username);
    }
}