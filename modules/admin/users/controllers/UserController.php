<?php

namespace Modules\Admin\Users\Controllers;

use Modules\Admin\Users\Models\UserModel;

class UserController extends \App\Controllers\BaseController
{

    private $user_model;

    public function __construct()
    {
        $this->user_model = new UserModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Users\Views\v_index', [
            'page_title'    => 'Data User'
        ]);
    }

    public function datatable()
    {
        $post_data  = $this->request->getPost();
        $data       = $this->user_model->getData($post_data);
        $num        = $post_data['start'];

        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item['id']."\">{$num}.";
            $row[]  = $item['username'];
            $row[]  = $item['email'];
            $row[]  = $item['level'];
            $row[]  = $item['last_login'];
            $row[]  = $item['created_at'];
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('user.view_change_pass', $item['id'])."\" class=\"btn btn-warning btn-xs mr-2\">Ganti Password</a>
                            <a href=\"".route_to('user.view_edit', $item['id'])."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"".route_to('user.view_delete', $item['id'])."\" class=\"btn btn-danger btn-xs\">Hapus</a>
                        </div>";
        }
    }
}