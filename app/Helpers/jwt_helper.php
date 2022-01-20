<?php

use App\Exceptions\ApiAccessErrorException;
use App\Libraries\PhpJwt;
use App\Libraries\PhpJwtKey;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Modules\Api\User\Models\UserModel;

function createAccessToken(array $data): string
{
    $iat = time();
    $exp = $iat + (int) Services::getAccessTokenLifetime();
    $payload = [
        'iss'   => 'Clamer_access_token',
        'aud'   => 'Auder_access_token',
        'iat'   => $iat,
        'exp'   => $exp,
        'data'  => $data
    ];
    $jwtToken = PhpJwt::encode($payload, Services::getAccessTokenKey(), 'HS256');
    return $jwtToken;
}

function createRefreshToken(array $data): string
{
    $payload    = [
        'iss'   => 'Clamer_refresh_token',
        'aud'   => 'Auder_refresh_token',
        'iat'   => time(),
        'data'  => $data
    ];
    $jwtToken = PhpJwt::encode($payload, Services::getAccessTokenKey(), 'HS256');
    return $jwtToken;
}

function getJwtFromAuthHeader(string $authHeader): string
{
    $authVal = explode(' ', $authHeader);
    if (is_null($authHeader) || $authVal[0] !== 'Bearer')
        throw new ApiAccessErrorException('Invalid JWT format', ResponseInterface::HTTP_UNAUTHORIZED);
    
    return $authVal[1];
}

function validateAccessToken($token): bool
{
    $decodedToken = PhpJwt::decode($token, new PhpJwtKey(Services::getAccessTokenKey(), 'HS256'));
    $userModel = new UserModel();
    return $userModel->checkUser($decodedToken->data->username);
}