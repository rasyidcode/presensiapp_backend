<?php

namespace Modules\Admin\Users\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Users\Models\UserModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class UserController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $userModel;

    public function __construct()
    {
        parent::__construct();

        // print_r($this->viewPath);die();

        $this->userModel = new UserModel();
    }

    public function index()
    {
        return $this->renderView('v_index', [
            'page_title'    => 'Data User',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-user'     => [
                    'url'       => route_to('user.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->userModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->username ?? '-';
            $row[]  = $item->email ?? '-';
            $badge = '';
            if ($item->level == 'admin') {
                $badge = 'success';
            } else if ($item->level == 'mahasiswa') {
                $badge = 'info';
            } else if ($item->level == 'dosen') {
                $badge = 'danger';
            }
            $row[]  = "<span class=\"badge badge-".$badge."\">".$item->level."</span>";
            $row[]  = $item->last_login ?? '-';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-warning btn-xs mr-2\">Ganti Password</a>
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-danger btn-xs\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->userModel->countData(),
                'recordsFiltered'   => $this->userModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        return $this->renderView('v_add', [
            'page_title'    => 'Tambah User',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-user'     => [
                    'url'       => route_to('user.list'),
                    'active'    => false,
                ],
                'tambah-user'     => [
                    'url'       => route_to('user.add'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'username'  => 'required|is_unique[users.username]',
            'password'  => 'required',
            'email'     => 'required',
            'level'     => 'required'
        ];
        // todo: add custom error messages
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $dataPost['password'] = password_hash($dataPost['password'], PASSWORD_BCRYPT);
        $this->userModel->create($dataPost);

        session()->setFlashdata('success', 'User created successfully!');
        return redirect()->back();
    }
}