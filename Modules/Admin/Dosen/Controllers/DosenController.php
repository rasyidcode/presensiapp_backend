<?php

namespace Modules\Admin\Dosen\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Users\Models\UserModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class DosenController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $dosenModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();

        $this->dosenModel = new DosenModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return $this->renderView('v_index', [
            'page_title'    => 'Data Dosen',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-dosen'     => [
                    'url'       => route_to('dosen.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->dosenModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->nip ?? '-';
            $row[]  = $item->nama_lengkap ?? '-';
            $row[]  = $item->tahun_masuk ?? '-';
            $row[]  = $item->jenis_kelamin ?? '-';
            $row[]  = $item->updated_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('dosen.edit', $item->id)."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" data-id=\"".$item->id."\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->dosenModel->countData(),
                'recordsFiltered'   => $this->dosenModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        return $this->renderView('v_add', [
            'page_title'    => 'Tambah Dosen',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-dosen'     => [
                    'url'       => route_to('dosen.list'),
                    'active'    => false,
                ],
                'tambah-dosen'   => [
                    'url'       => route_to('dosen.add'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'nip'               => 'required|is_unique[dosen.nip]',
            'nama_lengkap'      => 'required|is_unique[dosen.nama_lengkap]',
            'tahun_masuk'       => 'required',
            'jenis_kelamin'     => 'required',
            'alamat'            => 'required',
        ];
        
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $this->userModel->create([
            'username'  => $dataPost['nip'],
            'password'  => password_hash('12345', PASSWORD_BCRYPT),
            'level'     => 'dosen',
            'email'     => $dataPost['nip'] . '@presensiapp.my.id'
        ]);

        $lastID = $this->userModel->getLastID();
        $dataPost['id_user'] = $lastID;

        $this->dosenModel->create($dataPost);

        session()->setFlashdata('success', 'Dosen telah ditambahkan!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $dosen = $this->dosenModel
            ->builder('dosen')
            ->where('id', $id)
            ->get()
            ->getRowObject();
        
        return $this->renderView('v_edit', [
            'page_title'    => 'Edit Dosen',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-dosen'     => [
                    'url'       => route_to('dosen.list'),
                    'active'    => false,
                ],
                'edit-dosen'   => [
                    'url'       => route_to('dosen.edit', $id),
                    'active'    => true,
                ],
            ],
            'editData'      => $dosen
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nip'               => 'required',
            'nama_lengkap'      => 'required',
            'tahun_masuk'       => 'required',
            'jenis_kelamin'     => 'required',
            'alamat'            => 'required',
        ];
        
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $dosen = $this->dosenModel
            ->builder('dosen')
            ->where('id', $id)
            ->get()
            ->getRowObject();

        // check nip if already exist or not
        $checkNIP = $this->dosenModel
            ->builder('dosen')
            ->where('nip', $dataPost['nip'])
            ->where('id <>', $id)
            ->get()
            ->getRowObject();
        if (!is_null($checkNIP)) {
            session()->setFlashdata('error', ['NIP is already used!']);
            return redirect()->back();
        }

        $dataPost['updated_at'] = date('Y-m-d H:i:s');
        // update the dosen
        $this->dosenModel
            ->builder('dosen')
            ->where('id', $id)
            ->update($dataPost);

        // update the username based on the changed nip
        $this->userModel
            ->builder('users')
            ->where('id', $dosen->id_user)
            ->update([
                'username'  => $dataPost['nip']
            ]);

        session()->setFlashdata('success', 'Dosen telah diupdate!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $dosen = $this->dosenModel
            ->builder('dosen')
            ->where('id', $id)
            ->get()
            ->getRowObject();
        
        // delete dosen
        $this->dosenModel
            ->builder('dosen')
            ->where('id', $id)
            ->delete();
        
        // delete the user
        $this->userModel
            ->builder('users')
            ->where('id', $dosen->id_user)
            ->delete();

        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Data is deleted'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}