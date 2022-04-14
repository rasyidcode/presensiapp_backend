<?php

namespace Modules\Api\Shared\Controllers;

use App\Exceptions\ApiAccessErrorException;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Mhs\Models\AuthModel;
use Modules\Api\Shared\Models\BlacklistTokenModel;

class AuthController extends BaseController
{

    private $authModel;
    private $blacklistTokenModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->blacklistTokenModel = new BlacklistTokenModel();

        helper('jwt');
    }

    public function login()
    {
        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        $rulesMsg = [
            'username'  => [
                'required'  => 'Username tidak boleh kosong!'
            ],
            'password'  => [
                'required'  => 'Password tidak boleh kosong!'
            ]
        ];
        if (!$this->validate($rules, $rulesMsg))
            throw new ApiAccessErrorException(
                message: 'Error Validasi!',
                statusCode: ResponseInterface::HTTP_BAD_REQUEST,
                extras: ['errors'    => $this->validator->getErrors()]
            );

        $username   = $this->request->getVar('username');
        $password   = $this->request->getVar('password');

        // check mhs exist
        $mhsData = $this->authModel->getMahasiswa($username);
        if (is_null($mhsData))
            throw new ApiAccessErrorException(
                message: 'Username atau password salah!',
                statusCode: ResponseInterface::HTTP_UNAUTHORIZED
            );

        // check user password
        if (!password_verify($password, $mhsData->password))
            throw new ApiAccessErrorException(
                message: 'Username atau password salah!',
                statusCode: ResponseInterface::HTTP_UNAUTHORIZED
            );

        unset($userdata['password']);

        $accessToken = createAccessToken([
            'username'  => $mhsData->username,
            'name'      => $mhsData->nama_lengkap,
            'email'     => $mhsData->email
        ]);
        $refreshToken = createRefreshToken([
            'username'  => $mhsData->username
        ]);

        // update the refresh token
        $this->authModel->updateToken($mhsData->username, $refreshToken);

        // update last login
        $this->authModel->updateLastLogin($mhsData->username);

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

        // add token to the blacklisted
        $this->blacklistTokenModel->addToken($accessToken);
        // remove token from user's field
        $this->authModel->removeToken($refreshToken);

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
