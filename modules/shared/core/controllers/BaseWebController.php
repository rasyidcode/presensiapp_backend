<?php

namespace Modules\Shared\Core\Controllers;

use CodeIgniter\View\View;
use Config\View as ConfigView;
use \App\Controllers\BaseController;

class BaseWebController extends BaseController
{

    private $renderer;

    protected $viewPath;

    public function __construct()
    {
        /**
         * @var CodeIgniter\View\View $renderer
         */
        $this->renderer = new View(new ConfigView(), ROOTPATH);
    }

    protected function renderView(string $name, array $data = [], array $options = [])
    {
        $saveData = config(View::class)->saveData;

        if (array_key_exists('saveData', $options)) {
            $saveData = (bool) $options['saveData'];
            unset($options['saveData']);
        }
        // print_r($this->viewPath);die();
        $modulepath = strstr($this->viewPath, 'Modules');
        $modulepath = str_replace('Controllers', 'Views', $modulepath);
        $data['renderer'] = $this->renderer;

        return $this->renderer
            ->setData($data, 'raw')
            ->render($modulepath . '/' . $name, $options, $saveData);
    }
}
