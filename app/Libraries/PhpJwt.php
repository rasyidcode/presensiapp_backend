<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PhpJwt extends JWT {}

class PhpJwtKey extends Key {}

// class PhpJwtExpiredException extends ExpiredException {}