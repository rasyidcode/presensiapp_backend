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
        if (!$this->validate([
                'username'  => 'required',
                'password'  => 'required'
            ]))
            throw new ApiAccessErrorException(
                message: 'Validation error', 
                statusCode: ResponseInterface::HTTP_BAD_REQUEST,
                extras: ['errors'    => $this->validator->getErrors()]
            );

        $username   = $this->request->getVar('username');
        $password   = $this->request->getVar('password');

        $userdata = $this->authModel->getUser($username);
        if (is_null($userdata))
            throw new ApiAccessErrorException(
                message: 'Credentials not match', 
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );

        if (!password_verify($password, $userdata['password']))
            throw new ApiAccessErrorException(
                message: 'Credentials not match', 
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );

        return $this->response
            ->setJSON([
                'message'   => 'success'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}