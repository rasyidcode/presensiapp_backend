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
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"" . $item->id . "\">{$num}.";
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
            $row[]  = "<span class=\"badge badge-" . $badge . "\">" . $item->level . "</span>";
            $row[]  = $item->last_login ?? '-';
            $row[]  = $item->updated_at ?? '-';

            $actionContent = "<div class=\"text-center\">
                <a href=\"" . route_to('user.change-pass', $item->id) . "\" class=\"btn btn-warning btn-xs mr-2\">Ganti Password</a>
                <a href=\"" . route_to('user.edit', $item->id) . "\" class=\"btn btn-info btn-xs mr-2\">Edit</a>";
            if ($item->level == 'admin') {
                $actionContent .= "<a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" data-id=\"" . $item->id . "\">Hapus</a>";
            }
            $actionContent .= "</div>";

            $row[]  = $item->username != session()->get('username') ? $actionContent : "<div></div>";
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
            'email'     => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $dataPost['password']   = password_hash($dataPost['password'], PASSWORD_BCRYPT);
        $dataPost['level']      = 'admin';
        $this->userModel->create($dataPost);

        session()->setFlashdata('success', 'User created successfully!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $user = $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();

        if ($user->username == session()->get('username')) {
            return redirect()->route('user.list');
        }

        return $this->renderView('v_edit', [
            'page_title'    => 'Edit User',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-user'     => [
                    'url'       => route_to('user.list'),
                    'active'    => false,
                ],
                'edit-user'     => [
                    'url'       => route_to('user.edit', (int) $id),
                    'active'    => true,
                ],
            ],
            'editData'      => $user,
            'isNotAdmin'    => $user->level != 'admin'
        ]);
    }

    public function update($id)
    {
        $rules = [
            'username'  => 'required',
            'email'     => 'required'
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $user = $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();
        if ($user->username == session()->get('username')) {
            return redirect()->route('user.list');
        }

        $dataPost = $this->request->getPost();
        $dataPost['updated_at'] = date('Y-m-d H:i:s');
        $this->userModel
            ->builder('users')
            ->where('id',  (int) $id)
            ->update($dataPost);

        session()->setFlashdata('success', 'User updated successfully!');
        return redirect()->back();
    }

    public function changePass($id)
    {
        $user = $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();

        if ($user->username == session()->get('username')) {
            return redirect()->route('user.list');
        }

        return $this->renderView('v_change_pass', [
            'page_title'    => 'Change User Pass',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-user'     => [
                    'url'       => route_to('user.list'),
                    'active'    => false,
                ],
                'change-user-pass'     => [
                    'url'       => route_to('user.change-pass', (int) $id),
                    'active'    => true,
                ],
            ],
            'userId'    => $user->id
        ]);
    }

    public function doChangePass($id)
    {
        $rules = [
            'oldpassword'       => 'required',
            'newpassword'       => 'required',
            'renewpassword'     => 'required|matches[newpassword]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $user = $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();

        $dataPost = $this->request->getPost();
        if (!password_verify($dataPost['oldpassword'], $user->password)) {
            session()->setFlashdata('error', ['Old password is wrong']);
            return redirect()->back();
        }

        $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->update([
                'password'  => password_hash($dataPost['newpassword'], PASSWORD_BCRYPT)
            ]);

        session()->setFlashdata('success', 'User password is updated!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $user = $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();
        
        if ($user->username == session()->get('username')) {
            return redirect()->route('user.list');
        }

        if ($user->level != 'admin') {
            return redirect()->route('user.list');
        }

        $this->userModel
            ->builder('users')
            ->where('id', (int) $id)
            ->delete();

        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'User deleted!'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
