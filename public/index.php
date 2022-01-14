<?php

// Path to the front controller (this file)

use App\Exceptions\ApiAccessErrorException;

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// Ensure the current directory is pointing to the front controller's directory
chdir(__DIR__);

// Load our paths config file
// This is the line that might need to be changed, depending on your folder structure.
$pathsConfig = FCPATH . '../app/Config/Paths.php';
// ^^^ Change this if you move your application folder
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();

// Location of the framework bootstrap file.
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
// print_r($bootstrap);die();
$app       = require realpath($bootstrap) ?: $bootstrap;
// require_once(dirname(__FILE__).'/../modules/web/home/controllers/home_controller.php');
// require_once(dirname(__FILE__).'/../modules/web/home/views/home_view.php');
// print_r($app);die();
/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is setup, it's time to actually fire
 * up the engines and make this app do its thang.
 */
// try {
//     $app->run();
// } catch (MyException $e) {
//     return response
// 			->setJSON([
// 				'status'    => ResponseInterface::HTTP_OK,
// 				'message'   => $e->getMessage(),
// 			])
// 			->setStatusCode(ResponseInterface::HTTP_OK);
// }
// throw new ApiAccessErrorException();
$app->run();