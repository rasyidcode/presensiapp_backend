<?php

use App\Libraries\PhpJwt;
use Config\Services;

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

    $jwtToken = PhpJwt::encode($payload, Services::getAccessTokenKey(), ['HS256']);
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
    $jwtToken = PhpJwt::encode($payload, Services::getAccessTokenKey(), ['HS256']);
    return $jwtToken;
}