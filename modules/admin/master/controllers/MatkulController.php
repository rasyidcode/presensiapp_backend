<?php

namespace Modules\Admin\Master\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Master\Models\MatkulModel;

class MatkulController extends BaseController
{

    private $matkulModel;

    public function __construct()
    {
        $this->matkulModel = new MatkulModel();
    }

    public function index()
    {
        return view('\Modules\Admin\Master\Views\Matkul\v_index', [
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
            $row[]  = $item->kode_matkul ?? '-';
            $row[]  = $item->nama_matkul ?? '-';
            $row[]  = $item->created_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"".route_to('admin.error-404')."\" class=\"btn btn-danger btn-xs\">Hapus</a>
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
        return view('\Modules\Admin\Master\Views\Matkul\v_add', [
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
            'kode_matkul'  => 'required|is_unique[matkul.kode_matkul]',
            'nama_matkul'  => 'required|is_unique[matkul.nama_matkul]',
        ];
        // todo: add custom error messages
        if (!$this->validate($rules)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $this->matkulModel->create($dataPost);

        session()->setFlashdata('success', 'Matkul telah ditambahkan!');
        return redirect()->back();
    }
}