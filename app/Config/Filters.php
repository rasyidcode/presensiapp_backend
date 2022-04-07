<?php

namespace Config;

use App\Filters\ApiAuthFilter;
use App\Filters\ApiLogoutFilter;
use App\Filters\WebAuthFilter;
use App\Filters\WebLogoutFilter;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'                  => CSRF::class,
        'toolbar'               => DebugToolbar::class,
        'honeypot'              => Honeypot::class,

        'api_auth_filter'       => ApiAuthFilter::class,
        'api_logout_filter'     => ApiLogoutFilter::class,

        'web_auth_filter'       => WebAuthFilter::class,
        'web_logout_filter'     => WebLogoutFilter::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            'csrf',
            // 'honeypot',
            // 'authfilter'
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        // 'authfilter'    => [
        //     'before'    => [
        //         'api/v1/auth/*',
        //         'api/v1/auth'
        //     ]
        // ]
    ];
}
