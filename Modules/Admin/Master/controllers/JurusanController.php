<?php

namespace Modules\Admin\Master\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Master\Models\JurusanModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class JurusanController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $jurusanModel;

    public function __construct()
    {
        parent::__construct();

        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        return $this->renderView('jurusan/v_index', [
            'page_title'    => 'Data Jurusan',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'jurusan'      => [
                    'url'       => route_to('master.jurusan-list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->jurusanModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach($data as $item) {
            $num++;

            $row    = [];
            $row[]  = "<input type=\"hidden\" value=\"".$item->id."\">{$num}.";
            $row[]  = $item->kode ?? '-';
            $row[]  = $item->nama ?? '-';
            $row[]  = $item->updated_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('master.jurusan.edit', $item->id)."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" data-id=\"".$item->id."\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->jurusanModel->countData(),
                'recordsFiltered'   => $this->jurusanModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        return $this->renderView('jurusan/v_add', [
            'page_title'    => 'Tambah Jurusan',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jurusan'     => [
                    'url'       => route_to('master.jurusan.list'),
                    'active'    => false,
                ],
                'tambah-data'   => [
                    'url'       => route_to('master.jurusan.add'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'kode'  => 'required|is_unique[jurusan.kode]|max_length[5]',
            'nama'  => 'required|is_unique[jurusan.nama]',
        ];
        
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $this->jurusanModel->create($dataPost);

        session()->setFlashdata('success', 'Jurusan telah ditambahkan!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $jurusan = $this->jurusanModel->getByID((int) $id);
        return $this->renderView('jurusan/v_edit', [
            'page_title'    => 'Edit Jurusan',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-user'     => [
                    'url'       => route_to('master.jurusan.list'),
                    'active'    => false,
                ],
                'edit-jurusan'   => [
                    'url'       => route_to('master.jurusan.edit', $id),
                    'active'    => true,
                ],
            ],
            'editData'      => $jurusan
        ]);
    }

    public function update($id)
    {
        if (!$this->validate([
            'kode'  => 'required|max_length[5]',
            'nama'  => 'required'
        ])) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $checkIfKodeUsed = $this->jurusanModel->getByKode($dataPost['kode'], (int) $id);
        if (!is_null($checkIfKodeUsed)) {
            session()->setFlashdata('error', ['Kode '.$dataPost['kode'] . ' is already used!']);
            return redirect()->back();
        }
        
        $this->jurusanModel->updateJurusan($dataPost, (int) $id);

        session()->setFlashdata('success', 'Jurusan telah diperbaharui!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->jurusanModel->deleteJurusan((int) $id);
        
        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Data is removed.'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}