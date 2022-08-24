<?php

namespace Modules\Admin\Master\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Master\Models\MatkulModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class MatkulController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $matkulModel;

    public function __construct()
    {
        parent::__construct();

        $this->matkulModel = new MatkulModel();
    }

    public function index()
    {
        return $this->renderView('matkul/v_index', [
            'page_title'    => 'Data Matkul',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-matkul'      => [
                    'url'       => route_to('master.matkul.list'),
                    'active'    => true,
                ]
            ]
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->matkulModel->getData($postData);
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
                            <a href=\"".route_to('master.matkul.edit', $item->id)."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" data-id=\"".$item->id."\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->matkulModel->countData(),
                'recordsFiltered'   => $this->matkulModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        return $this->renderView('matkul/v_add', [
            'page_title'    => 'Tambah Data',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-matkul'   => [
                    'url'       => route_to('master.matkul.list'),
                    'active'    => false,
                ],
                'tambah-data'   => [
                    'url'       => route_to('master.matkul.add'),
                    'active'    => true,
                ],
            ]
        ]);
    }

    public function create()
    {
        $rules = [
            'kode'  => 'required|is_unique[matkul.kode]',
            'nama'  => 'required|is_unique[matkul.nama]',
        ];
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $this->matkulModel->create($dataPost);

        session()->setFlashdata('success', 'Matkul telah ditambahkan!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $matkul = $this->matkulModel
            ->builder('matkul')
            ->where('id', (int) $id)
            ->get()
            ->getRowObject();
        
        return $this->renderView('matkul/v_edit', [
            'page_title'    => 'Tambah Data',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-matkul'   => [
                    'url'       => route_to('master.matkul.list'),
                    'active'    => false,
                ],
                'edit-data'   => [
                    'url'       => route_to('master.matkul.edit', (int) $id),
                    'active'    => true,
                ],
            ],
            'editData'  => $matkul
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
        $checkIfKodeUsed = $this->matkulModel
            ->builder('matkul')
            ->where('kode', $dataPost['kode'])
            ->where('id <>', (int) $id)
            ->get()
            ->getRowObject();
        if (!is_null($checkIfKodeUsed)) {
            session()->setFlashdata('error', ['Kode '.$dataPost['kode'] . ' is already used!']);
            return redirect()->back();
        }
        
        $dataPost['updated_at'] = date('Y-m-d H:i:s');
        $this->matkulModel
            ->builder('matkul')
            ->where('id', $id)
            ->update($dataPost);

        session()->setFlashdata('success', 'Matkul telah diperbaharui!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->matkulModel
            ->builder('matkul')
            ->where('id', $id)
            ->delete();
        
        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Data is removed.'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}