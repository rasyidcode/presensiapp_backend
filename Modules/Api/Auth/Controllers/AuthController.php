<?php

namespace Modules\Api\Auth\Controllers;

use App\Exceptions\ApiAccessErrorException;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Api\Auth\Models\AuthModel;
use Modules\Api\Shared\Models\BlacklistTokenModel;
use Modules\Api\Shared\Models\UserModel;

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

        // check user exist
        $userdata = $this->authModel->getUser($username);
        if (is_null($userdata))
            throw new ApiAccessErrorException(
                message: 'Username atau password salah!',
                statusCode: ResponseInterface::HTTP_UNAUTHORIZED
            );

        // check user password
        if (!password_verify($password, $userdata->password))
            throw new ApiAccessErrorException(
                message: 'Username atau password salah!',
                statusCode: ResponseInterface::HTTP_UNAUTHORIZED
            );

        unset($userdata->password);
        
        $additionalData = null;
        if ($userdata->level == 'mahasiswa') {
            $additionalData = $this->authModel->getDataMahasiswa($userdata->id);
        } else if ($userdata->level == 'dosen') {
            $additionalData = $this->authModel->getDataDosen($userdata->id);
        }
        
        $accessToken = createAccessToken([
            'id'        => $userdata->id,
            'username'  => $userdata->username,
            'name'      => $additionalData->nama_lengkap,
            'level'     => $userdata->level,
            'email'     => $userdata->email
        ]);
        $refreshToken = createRefreshToken([
            'username'  => $userdata->username
        ]);

        // update the refresh token
        $this->authModel->updateToken($userdata->username, $refreshToken);

        // update last login
        $this->authModel->updateLastLogin($userdata->username);

        return $this->response
            ->setJSON([
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function logout()
    {
        $accessToken = $this->request->header('Access-Token')->getValue();
        $refreshToken = $this->request->header('Refresh-Token');
        if (is_null($refreshToken))
            throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);

        $refreshToken = $refreshToken->getValue();
        if (empty($refreshToken))
            throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);

        $userModel = new UserModel();
        if (!$userModel->checkUserByRefreshToken($refreshToken))
            throw new ApiAccessErrorException('Refresh token not found', ResponseInterface::HTTP_BAD_REQUEST);

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
    public function renewToken()
    {
        $refreshToken = $this->request->header('Refresh-Token');
        if (is_null($refreshToken))
            throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);

        $refreshToken = $refreshToken->getValue();
        if (empty($refreshToken))
            throw new ApiAccessErrorException('Please provide your refresh token', ResponseInterface::HTTP_BAD_REQUEST);

        $userdata = $this->authModel->getUserByRf($refreshToken);
        if(is_null($userdata)) {
            throw new ApiAccessErrorException('User not found with provided refresh token', ResponseInterface::HTTP_NOT_FOUND);
        }

        unset($userdata->password);

        $additionalData = null;
        if ($userdata->level == 'mahasiswa') {
            $additionalData = $this->authModel->getDataMahasiswa($userdata->id);
        } else if ($userdata->level == 'dosen') {
            $additionalData = $this->authModel->getDataDosen($userdata->id);
        }

        $accessToken = createAccessToken([
            'id'        => $userdata->id,
            'username'  => $userdata->username,
            'name'      => $additionalData->nama_lengkap,
            'level'     => $userdata->level,
            'email'     => $userdata->email
        ]);

        return $this->response
            ->setJSON([
                'access_token'  => $accessToken
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
