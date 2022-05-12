<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

/**
 * -------------------------------------------------------------------
 * AUTOLOADER CONFIGURATION
 * -------------------------------------------------------------------
 *
 * This file defines the namespaces and class maps so the Autoloader
 * can find the files as needed.
 *
 * NOTE: If you use an identical key in $psr4 or $classmap, then
 * the values in this file will overwrite the framework's values.
 */
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     * This maps the locations of any namespaces in your application to
     * their location on the file system. These are used by the autoloader
     * to locate files the first time they have been instantiated.
     *
     * The '/app' and '/system' directories are already mapped for you.
     * you may change the name of the 'App' namespace if you wish,
     * but this should be done prior to creating any namespaced classes,
     * else you will need to modify all of those classes for this to work.
     *
     * Prototype:
     *```
     *   $psr4 = [
     *       'CodeIgniter' => SYSTEMPATH,
     *       'App'	       => APPPATH
     *   ];
     *```
     *
     * @var array<string, string>
     */
    public $psr4 = [
        APP_NAMESPACE   => APPPATH, // For custom app namespace
        'Config'        => APPPATH . 'Config',
        'Modules'       => MODULESPATH
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     * The class map provides a map of class names and their exact
     * location on the drive. Classes loaded in this manner will have
     * slightly faster performance because they will not have to be
     * searched for within one or more directories as they would if they
     * were being autoloaded through a namespace.
     *
     * Prototype:
     *```
     *   $classmap = [
     *       'MyClass'   => '/path/to/class/file.php'
     *   ];
     *```
     *
     * @var array<string, string>
     */
    public $classmap = [
        'Firebase\JWT\JWT'                  => APPPATH . 'ThirdParty/php-jwt/src/JWT.php',
        'Firebase\JWT\Key'                  => APPPATH . 'ThirdParty/php-jwt/src/Key.php',
        'Firebase\JWT\ExpiredException'     => APPPATH . 'ThirdParty/php-jwt/src/ExpiredException.php',
    ];

    /**
     * -------------------------------------------------------------------
     * Files
     * -------------------------------------------------------------------
     * The files array provides a list of paths to __non-class__ files
     * that will be autoloaded. This can be useful for bootstrap operations
     * or for loading functions.
     *
     * Prototype:
     * ```
     *	  $files = [
     *	 	   '/path/to/my/file.php',
     *    ];
     * ```
     *
     * @var array<int, string>
     */
    public $files = [
        // APPPATH.'../modules/*.php'
        APPPATH . '/Helpers/myview_helper.php',
    ];

    public function __construct()
    {
        parent::__construct();

        // load all namespace to psr4 on modules path
        $scannedDir = scandir(MODULESPATH);
        foreach($scannedDir as $module) {
            if ($module == '.' || $module == '..') {
                continue;
            }

            $moduleDir = scandir(MODULESPATH . $module);
            foreach($moduleDir as $subModule) {
                if ($subModule == '.' || $subModule == '..' || $subModule == 'routes.php') {
                    continue;
                }

                $basemodulenamespace    = 'Modules\\' . ucfirst($module) . '\\' . ucfirst($subModule) . '\\';
                $basemodulepath         = MODULESPATH . $module . DIRECTORY_SEPARATOR . $subModule . DIRECTORY_SEPARATOR;

                $submodulecontrollerspath               = $basemodulepath . 'controllers';
                if (file_exists($submodulecontrollerspath)) {
                    $controllersNamespace               = $basemodulenamespace . 'Controllers';
                    $this->psr4[$controllersNamespace]  = $submodulecontrollerspath;
                }

                $submodulemodelspath                = $basemodulepath . 'models';
                if (file_exists($submodulemodelspath)) {
                    $modelsNamespace                = $basemodulenamespace . 'Models';
                    $this->psr4[$modelsNamespace]   = $submodulemodelspath;
                }

                // $submoduleviewspath                = $basemodulepath . 'views';
                // if (file_exists($submoduleviewspath)) {
                //     $viewsNamespace                = $basemodulenamespace . 'Views';
                //     $this->psr4[$viewsNamespace]   = $submoduleviewspath;
                // }
            }
        }
    }
}
