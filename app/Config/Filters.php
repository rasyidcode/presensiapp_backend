<?php

namespace Config;

use App\Filters\ApiAdminNotAllowed;
use App\Filters\ApiAuthDsnFilter;
use App\Filters\ApiAuthFilter;
use App\Filters\ApiAuthMhsFilter;
use App\Filters\ApiLogoutFilter;
use App\Filters\LevelFilter;
use App\Filters\MhsOnly;
use App\Filters\WebAuthFilter;
use App\Filters\WebLogoutFilter;
use App\Filters\WebRedirectIfAuth;
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
        'csrf'                          => CSRF::class,
        'toolbar'                       => DebugToolbar::class,
        'honeypot'                      => Honeypot::class,

        'api-auth-filter'               => ApiAuthFilter::class,
        'api-auth-mhs-filter'           => ApiAuthMhsFilter::class,
        'api-auth-dsn-filter'           => ApiAuthDsnFilter::class,

        'web-auth-filter'               => WebAuthFilter::class,
        'web_logout_filter'             => WebLogoutFilter::class,
        'web-redirect-if-auth-filter'   => WebRedirectIfAuth::class,

        'level-filter'                  => LevelFilter::class,

    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            'csrf' => [
                'except'    => [
                    'api/v1/*',
                    '*/get-data',
                    '*/*/get-data',
                ]
            ],
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
