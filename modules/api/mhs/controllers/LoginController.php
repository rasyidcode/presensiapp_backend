<?php

namespace Modules\Api\Login\Controllers;

use App\Exceptions\ApiAccessErrorException;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Mhs\Models\LoginModel;
use Modules\Api\Shared\Models\BlacklistTokenModel;

class LoginController extends BaseController
{

    private $authModel;
    private $blacklistTokenModel;

    public function __construct()
    {
        $this->authModel = new LoginModel();
        $this->blacklistTokenModel = new BlacklistTokenModel();

        helper('jwt');
    }

    public function login()
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

        $accessToken = createAccessToken([
            'username' => $userdata['username'],
            'name'  => $userdata['nama_lengkap'],
            'level' => $userdata['level']
        ]);
        $refreshToken = createRefreshToken([
            'username'  => $userdata['username']
        ]);

        // update the refresh token
        $tokenUpdate = $this->authModel->updateToken($userdata['username'], $refreshToken);
        if (!$tokenUpdate)
            throw new ApiAccessErrorException(
                message: 'Failed to update token, please contact your admin for details',
                statusCode: ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            );

        // update last login
        $lastLoginUpdate = $this->authModel->updateLastLogin($userdata['username']);
        if (!$lastLoginUpdate)
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

    public function logout()
    {
        $accessToken = $this->request->header('AccessToken')->getValue();
        $refreshToken = $this->request->header('RefreshToken')->getValue();

        $insertRes = $this->blacklistTokenModel->addToken($accessToken);
        if (!$insertRes)
            throw new ApiAccessErrorException('Failed to sign out', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        
        $removeTokenRes = $this->authModel->removeTokenByRf($refreshToken);
        if (!$removeTokenRes)
            throw new ApiAccessErrorException('Failed to sign out', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);

        return $this->response
            ->setJSON([
                'message'   => 'You signed out'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
    
    /**
     * route -> auth/renewToken
     */

    /**
     * route -> auth/forgotPassword
     */
}