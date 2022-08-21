<?php

namespace Modules\Admin\Mahasiswa\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Mahasiswa\Models\MahasiswaModel;
use Modules\Admin\Master\Models\JurusanModel;
use Modules\Admin\Users\Models\UserModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class MahasiswaController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $mahasiswaModel;
    private $userModel;
    private $jurusanModel;

    public function __construct()
    {
        parent::__construct();

        $this->mahasiswaModel   = new MahasiswaModel();
        $this->userModel        = new UserModel();
        $this->jurusanModel     = new JurusanModel();
    }

    public function index()
    {
        return $this->renderView('v_index', [
            'page_title'    => 'Data Mahasiswa',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-mahasiswa'     => [
                    'url'       => route_to('mahasiswa.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->mahasiswaModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->nim ?? '-';
            $row[]  = $item->nama_lengkap ?? '-';
            $row[]  = $item->nama_jurusan ?? '-';
            $row[]  = $item->tahun_masuk ?? '-';
            $row[]  = $item->jenis_kelamin ?? '-';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('mahasiswa.edit', $item->id)."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" data-id=\"".$item->id."\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->mahasiswaModel->countData(),
                'recordsFiltered'   => $this->mahasiswaModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        $jurusanList = $this->jurusanModel->getList();
        return $this->renderView('v_add', [
            'page_title'    => 'Tambah Mahasiswa',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-mahasiswa'     => [
                    'url'       => route_to('mahasiswa.list'),
                    'active'    => false,
                ],
                'tambah-mahasiswa'   => [
                    'url'       => route_to('mahasiswa.add'),
                    'active'    => true,
                ],
            ],
            'jurusanList'   => $jurusanList
        ]);
    }

    public function create()
    {
        $rules = [
            'nim'               => 'required|is_unique[mahasiswa.nim]',
            'nama_lengkap'      => 'required|is_unique[mahasiswa.nama_lengkap]',
            'id_jurusan'        => 'required',
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
            'username'  => $dataPost['nim'],
            'password'  => password_hash('12345', PASSWORD_BCRYPT),
            'level'     => 'mahasiswa',
            'email'     => $dataPost['nim'] . '@presensiapp.my.id'
        ]);

        $lastID = $this->userModel->getLastID();
        $dataPost['id_user'] = $lastID;

        $this->mahasiswaModel->create($dataPost);

        session()->setFlashdata('success', 'Mahasiswa telah ditambahkan!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $jurusanList = $this->jurusanModel->getList();
        $mahasiswa = $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('id', $id)
            ->get()
            ->getRowObject();
        return $this->renderView('v_edit', [
            'page_title'    => 'Edit Mahasiswa',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-mahasiswa'     => [
                    'url'       => route_to('mahasiswa.list'),
                    'active'    => false,
                ],
                'edit-mahasiswa'   => [
                    'url'       => route_to('mahasiswa.edit', $id),
                    'active'    => true,
                ],
            ],
            'jurusanList'   => $jurusanList,
            'editData'      => $mahasiswa
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nim'               => 'required',
            'nama_lengkap'      => 'required',
            'id_jurusan'        => 'required',
            'tahun_masuk'       => 'required',
            'jenis_kelamin'     => 'required',
            'alamat'            => 'required',
        ];
        
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $mahasiswa = $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('id', $id)
            ->get()
            ->getRowObject();

        // check nim if already exist or not
        $checkNIM = $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('nim', $dataPost['nim'])
            ->where('id <>', $id)
            ->get()
            ->getRowObject();
        if (!is_null($checkNIM)) {
            session()->setFlashdata('error', ['NIM is already used!']);
            return redirect()->back();
        }

        // update the mahasiswa
        $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('id', $id)
            ->update($dataPost);

        // update the username based on the changed nim
        $this->userModel
            ->builder('users')
            ->where('id', $mahasiswa->id_user)
            ->update([
                'username'  => $dataPost['nim']
            ]);

        session()->setFlashdata('success', 'Mahasiswa telah diupdate!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $mahasiswa = $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('id', $id)
            ->get()
            ->getRowObject();
        
        // delete mahasiswa
        $this->mahasiswaModel
            ->builder('mahasiswa')
            ->where('id', $id)
            ->delete();
        
        // delete the user
        $this->userModel
            ->builder('users')
            ->where('id', $mahasiswa->id_user)
            ->delete();

        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Data is deleted'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}