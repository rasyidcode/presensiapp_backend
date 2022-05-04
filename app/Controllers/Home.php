<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // test
        return view('welcome_message');
    }
}
