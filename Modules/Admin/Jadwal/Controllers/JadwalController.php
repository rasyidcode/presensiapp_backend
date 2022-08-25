<?php

namespace Modules\Admin\Jadwal\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Admin\Dosen\Models\DosenModel;
use Modules\Admin\Jadwal\Models\JadwalModel;
use Modules\Admin\Kelas\Models\KelasModel;
use Modules\Admin\Master\Models\MatkulModel;
use Modules\Shared\Core\Controllers\BaseWebController;

class JadwalController extends BaseWebController
{

    protected $viewPath = __DIR__;

    private $jadwalModel;
    private $matkulModel;
    private $dosenModel;
    private $kelasModel;

    public function __construct()
    {
        parent::__construct();

        $this->jadwalModel  = new JadwalModel();
        $this->matkulModel  = new MatkulModel();
        $this->dosenModel   = new DosenModel();
        $this->kelasModel   = new KelasModel();
    }

    public function index()
    {
        $matkulList = $this->matkulModel->getAll();
        $dosenList = $this->dosenModel->getList();
        return $this->renderView('v_index', [
            'page_title'    => 'Data Jadwal',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jadwal'     => [
                    'url'       => route_to('jadwal.list'),
                    'active'    => true,
                ]
            ],
            'matkulList'    => $matkulList,
            'dosenList'    => $dosenList,
        ]);
    }

    public function getData()
    {
        $postData   = $this->request->getPost();
        $data       = $this->jadwalModel->getData($postData);
        $num        = $postData['start'];

        $resData = [];
        foreach ($data as $item) {
            $num++;

            $row    = [];
            $row[]  = $num . '.';
            $row[]  = $item->date ?? '-';
            $row[]  = $item->begin_time ?? '-';
            $row[]  = $item->end_time ?? '-';
            $row[]  = $item->matkul ?? '-';
            $row[]  = $item->dosen ?? '-';
            $row[]  = $item->mahasiswa_total ?? '0';
            $row[]  = $item->updated_at ?? '-';
            $row[]  = "<div class=\"text-center\">
                            <a href=\"" . route_to('jadwal.edit', $item->id) . "\" class=\"btn btn-info btn-xs mr-2\">Edit</a>
                            <a href=\"javascript:void(0);\" class=\"btn btn-danger btn-xs\" data-id=\"".$item->id."\">Hapus</a>
                        </div>";
            $resData[] = $row;
        }

        return $this->response
            ->setJSON([
                'draw'              => $postData['draw'],
                'recordsTotal'      => $this->jadwalModel->countData($postData),
                'recordsFiltered'   => $this->jadwalModel->countFilteredData($postData),
                'data'              => $resData
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        $kelasList = $this->kelasModel->getList();
        return $this->renderView('v_add', [
            'page_title'    => 'Tambah Jadwal',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jadwal'     => [
                    'url'       => route_to('jadwal.list'),
                    'active'    => false,
                ],
                'tambah-jadwal'   => [
                    'url'       => route_to('jadwal.add'),
                    'active'    => true,
                ],
            ],
            'kelasList' => $kelasList
        ]);
    }

    public function create()
    {
        $rules = [
            'kelas'         => 'required',
            'tanggal'       => 'required',
            'beginTime'     => 'required',
            'endTime'       => 'required',
        ];

        $messages = [
            'kelas' => [
                'required'  => 'Kelas tidak boleh kosong!'
            ],
            'tanggal' => [
                'required'  => 'Tanggal tidak boleh kosong!'
            ],
            'beginTime' => [
                'required'  => 'Jam Mulai tidak boleh kosong!'
            ],
            'endTime' => [
                'required'  => 'Jam Selesai tidak boleh kosong!'
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();

        $this->jadwalModel->create([
            'id_kelas'      => $dataPost['kelas'],
            'date'          => $dataPost['tanggal'],
            'begin_time'    => $dataPost['beginTime'],
            'end_time'      => $dataPost['endTime']
        ]);

        session()->setFlashdata('success', 'Jadwal telah ditambahkan!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $kelasList = $this->kelasModel->getList();
        $jadwal = $this->kelasModel
            ->builder('jadwal')
            ->select('
                *,
                SUBSTR(jadwal.begin_time, 1, 5) as jam_mulai,
                SUBSTR(jadwal.end_time, 1, 5) as jam_selesai
            ')
            ->where('id', $id)
            ->get()
            ->getRowObject();

        return $this->renderView('v_edit', [
            'page_title'    => 'Edit Jadwal',
            'pageLinks'    => [
                'home'      => [
                    'url'       => route_to('admin.welcome'),
                    'active'    => false,
                ],
                'data-jadwal'     => [
                    'url'       => route_to('jadwal.list'),
                    'active'    => false,
                ],
                'edit-jadwal'   => [
                    'url'       => route_to('jadwal.edit', $id),
                    'active'    => true,
                ],
            ],
            'kelasList' => $kelasList,
            'editData' => $jadwal
        ]);
    }

    public function update($id)
    {
        $rules = [
            'kelas'         => 'required',
            'tanggal'       => 'required',
            'beginTime'     => 'required',
            'endTime'       => 'required',
        ];

        $messages = [
            'kelas' => [
                'required'  => 'Kelas tidak boleh kosong!'
            ],
            'tanggal' => [
                'required'  => 'Tanggal tidak boleh kosong!'
            ],
            'beginTime' => [
                'required'  => 'Jam Mulai tidak boleh kosong!'
            ],
            'endTime' => [
                'required'  => 'Jam Selesai tidak boleh kosong!'
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->back();
        }

        $dataPost = $this->request->getPost();
        $this->jadwalModel
            ->builder('jadwal')
            ->where('id', $id)
            ->update([
                'id_kelas'      => $dataPost['kelas'],
                'date'          => $dataPost['tanggal'],
                'begin_time'    => $dataPost['beginTime'],
                'end_time'      => $dataPost['endTime']
            ]);

        session()->setFlashdata('success', 'Jadwal telah diperbaharui!');
        return redirect()->back();
    }

    public function delete($id)
    {
        $dosenQR = $this->jadwalModel
            ->builder('dosen_qrcode')
            ->where('id_jadwal', $id)
            ->get()
            ->getRowObject();
        if (!is_null($dosenQR)) {
            return $this->response
                ->setJSON([
                    'success'   => false,
                    'message'   => 'Data is used by another entity'
                ])
                ->setStatusCode(ResponseInterface::HTTP_OK);
        }

        $this->jadwalModel
            ->builder('jadwal')
            ->where('id', $id)
            ->delete();
        
        return $this->response
            ->setJSON([
                'success'   => true,
                'message'   => 'Data is deleted'
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
