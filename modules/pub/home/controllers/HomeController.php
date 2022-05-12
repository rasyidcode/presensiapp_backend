<?php

namespace Modules\Pub\Home\Controllers;

use Modules\Shared\Core\Controllers\BaseWebController;

class HomeController extends BaseWebController
{

    protected $viewPath = __DIR__;

    public function index()
    {
        return $this->renderView('v_home');
    }
}