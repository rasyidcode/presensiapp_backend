<?php

namespace Modules\Api\Auth\Controllers;

use App\Exceptions\ApiAccessErrorException;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Auth\Models\AuthModel;

class AuthController extends BaseController
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
        try {
            if (!$this->validate([
                    'username'  => 'required',
                    'password'  => 'required'
                ]))
                throw new ApiAccessErrorException(
                    'Validation error', 
                    ResponseInterface::HTTP_BAD_REQUEST,
                    ['errors'    => $this->validator->getErrors()]
                );

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
        } catch (ApiAccessErrorException $e) {
            $response = ['message'   => $e->getMessage()];

            if (!empty($e->getExtras()))
                $response = array_merge($response, $e->getExtras());

            return $this->response
                    ->setJSON($response)
                    ->setStatusCode($e->getHttpCode());
        }
    }
}