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

        helper('jwt');
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

    /**
     * route -> auth/signIn
     */
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

        // check username exist or not
        if (is_null($userdata))
            throw new ApiAccessErrorException(
                message: 'Credentials not match', 
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );
        
        // check user password
        if (!password_verify($password, $userdata['password']))
            throw new ApiAccessErrorException(
                message: 'Credentials not match', 
                statusCode: ResponseInterface::HTTP_BAD_REQUEST
            );

        unset($userdata['password']);

        $accessToken = createAccessToken($userdata);
        $refreshToken = createRefreshToken([
            'username'  => $userdata['username']
        ]);

        // update the refresh token
        $token_update = $this->authModel->updateToken($userdata['username'], $refreshToken);
        if (!$token_update)
            throw new ApiAccessErrorException(
                message: 'Failed to update token, please contact your admin for details',
                statusCode: ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );

        // update last login
        $last_login_update = $this->authModel->updateLastLogin($userdata['username']);
        if (!$last_login_update)
            throw new ApiAccessErrorException(
                message: 'Failed to update last login, please contact your admin for details',
                statusCode: ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );

        return $this->response
            ->setJSON([
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    /**
     * route -> auth/signOut
     */

    /**
     * route -> auth/renewToken
     */

    /**
     * route -> auth/deleteToken
     */

    /**
     * route -> auth/forgotPassword
     */
}