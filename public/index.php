<?php

// Path to the front controller (this file)

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
try {
    $app->run();
} catch (App\Exceptions\ApiAccessErrorException $e) {
    $response = ['message' => $e->getMessage()];

    if (!empty($e->getExtras()))
        $response = array_merge($response, $e->getExtras());

    header('Content-Type: application/json');
    if ($e->getStatusCode() > 0) {
        // header("HTTP/1.1 {$e->getStatusCode()}", true, $e->getStatusCode());
        header(sprintf("HTTP/%s %s %s", $_SERVER['SERVER_PROTOCOL'], $e->getStatusCode(), $e->getReason()), true, $e->getStatusCode());
    }
    
    echo json_encode($response);
}