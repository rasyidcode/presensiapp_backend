<?php

namespace Modules\Api\Auth\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Exception;
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
        throw new Exception('haha');
        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->response
                ->setJSON([
                    'message'   => 'Validation error',
                    'errors'    => $this->validator->getErrors()
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $username   = $this->request->getVar('username');
        $password   = $this->request->getVar('password');

        $userdata = $this->authModel->getUser($username);
        if (is_null($userdata)) {
            return $this->response
                ->setJSON([
                    'message'   => 'Credentials not match'
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if (!password_verify($password, $userdata['password'])) {
            return $this->response
                ->setJSON([
                    'message'   => 'Credentials not match'
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    }
}