<?php

namespace Modules\Admin\Kelas\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class KelasModel extends Model
{

    private $tblName = 'kelas';

    private $columnOrder   = [null, 'kelas', 'matkul', 'dosen', 'created_at', null];
    private $columnMahasiswaOrder   = [null, 'nim', 'nama_lengkap', null, 'jenis_kelamin', null];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get kelas list with datatable params
     * 
     * @param array $dtParams
     * 
     * @return array|null
     */
    public function getData(array $dtParams): ?array
    {
        $kelas = $this->builder($this->tblName);

        $kelas->select('
            kelas.id,
            kelas.created_at,
            dosen.nama_lengkap as dosen,
            matkul.kode as kelas,
            matkul.nama as matkul
        ');

        $kelas->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $kelas->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            $kelas->groupStart();
            // nama kelas
            $kelas->like('matkul.kode', $dtParams['search']['value']);
            // nama matkul
            $kelas->orLike('matkul.nama', $dtParams['search']['value']);
            // nama dosen
            $kelas->orLike('dosen.nama_lengkap', $dtParams['search']['value']);
            $kelas->groupEnd();
        }

        if (isset($dtParams['order'])) {
            $kelas->orderBy($this->columnOrder[$dtParams['order']['0']['column']], $dtParams['order']['0']['dir']);
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelas->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelas->where('kelas.deleted_at', null);

        return $kelas->get()->getResultObject();
    }

    /**
     * count filtered data
     * 
     * @param array $dtParams
     * 
     * @return int|null
     */
    public function countFilteredData(array $dtParams): int
    {
        $kelas = $this->builder($this->tblName);

        $kelas->join('dosen', 'kelas.id_dosen = dosen.id', 'left');
        $kelas->join('matkul', 'kelas.id_matkul = matkul.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            $kelas->groupStart();
            // nama kelas
            $kelas->like('matkul.kode', $dtParams['search']['value']);
            // nama matkul
            $kelas->orLike('matkul.nama', $dtParams['search']['value']);
            // nama dosen
            $kelas->orLike('dosen.nama_lengkap', $dtParams['search']['value']);
            $kelas->groupEnd();
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelas->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelas->where('kelas.deleted_at', null);

        return $kelas->countAllResults();
    }

    /**
     * count total data
     * 
     * @param array $dt_params
     * 
     * @return int
     */
    public function countData(): int
    {
        return $this->builder($this->tblName)
            ->countAllResults();
    }

    /**
     * Create new mahasiswa
     * 
     * @param array $data
     */
    public function create(array $data)
    {
        $this->db->table($this->tblName)
            ->insert($data);
    }

    /**
     * Check kelas exist
     * 
     * @param int $matkulId
     * @param int $dosenId
     * 
     * @return bool
     */
    public function checkKelas(int $matkulId, int $dosenId): bool
    {
        $result = $this->db->table($this->tblName)
            ->selectCount('id', 'count')
            ->where('id_matkul', $matkulId)
            ->where('id_dosen', $dosenId)
            ->get()
            ->getRowObject();
        return is_null($result) ? false : (isset($result->count) ? $result->count > 0 : false);
    }

    /**
     * Get kelas by ID
     * 
     * @param int $id
     * 
     * @return object|null
     */
    public function get(int $id): ?object
    {
        $kelas = $this->db->table($this->tblName);
        $kelas->select('
            kelas.id,
            dosen.nama_lengkap as dosen_pengajar,
            matkul.kode as nama_kelas,
            matkul.nama as nama_matkul,
            kelas.created_at,
        ');
        $kelas->join('matkul', 'matkul.id = kelas.id_matkul', 'left');
        $kelas->join('dosen', 'dosen.id = kelas.id_dosen', 'left');
        $kelas->where('kelas.id', $id);
        $kelas->where('kelas.deleted_at', null);

        return $kelas->get()->getRowObject();
    }

    /**
     * Get mahasiswa data by kelas id
     * 
     * @param int $kelasId
     * @param array $dtParams
     * 
     * @return object|null
     */
    public function getMahasiswaData(int $kelasId, array $dtParams)
    {
        $kelasMahasiswa = $this->builder('kelas_mahasiswa');

        $kelasMahasiswa->select('
            kelas.id as id_kelas,
            mahasiswa.nim,
            mahasiswa.nama_lengkap,
            jurusan.nama as jurusan,
            mahasiswa.jenis_kelamin,
            kelas.created_at
        ');

        $kelasMahasiswa->join('mahasiswa', 'kelas_mahasiswa.id_mahasiswa = mahasiswa.id', 'left');
        $kelasMahasiswa->join('jurusan', 'mahasiswa.id_jurusan = jurusan.id', 'left');
        $kelasMahasiswa->join('kelas', 'kelas_mahasiswa.id_kelas = kelas.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            $kelasMahasiswa->groupStart();
            $kelasMahasiswa->like('mahasiswa.nim', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('mahasiswa.nama_lengkap', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('jurusan.nama', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('mahasiswa.jenis_kelamin', $dtParams['search']['value']);
            $kelasMahasiswa->groupEnd();
        }

        if (isset($dtParams['order'])) {
            $kelasMahasiswa->orderBy(
                $this->columnMahasiswaOrder[$dtParams['order']['0']['column']],
                $dtParams['order']['0']['dir']
            );
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelasMahasiswa->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelasMahasiswa->where('kelas_mahasiswa.id_kelas', $kelasId);

        return $kelasMahasiswa->get()->getResultObject();
    }

    /**
     * count filtered data
     * 
     * @param int $kelasId
     * @param array $dtParams
     * 
     * @return int|null
     */
    public function mahasiswaCountFilteredData(int $kelasId, array $dtParams): int
    {
        $kelasMahasiswa = $this->builder('kelas_mahasiswa');

        $kelasMahasiswa->join('mahasiswa', 'kelas_mahasiswa.id_mahasiswa = mahasiswa.id', 'left');
        $kelasMahasiswa->join('jurusan', 'mahasiswa.id_jurusan = jurusan.id', 'left');
        $kelasMahasiswa->join('kelas', 'kelas_mahasiswa.id_kelas = kelas.id', 'left');

        if (isset($dtParams['search']) && !empty($dtParams['search'])) {
            $kelasMahasiswa->groupStart();
            $kelasMahasiswa->like('mahasiswa.nim', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('mahasiswa.nama_lengkap', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('jurusan.nama', $dtParams['search']['value']);
            $kelasMahasiswa->orLike('mahasiswa.jenis_kelamin', $dtParams['search']['value']);
            $kelasMahasiswa->groupEnd();
        }

        if (isset($dtParams['order'])) {
            $kelasMahasiswa->orderBy(
                $this->columnMahasiswaOrder[$dtParams['order']['0']['column']],
                $dtParams['order']['0']['dir']
            );
        }

        if (isset($dtParams['length']) && isset($dtParams['start'])) {
            if ($dtParams['length'] !== -1) {
                $kelasMahasiswa->limit($dtParams['length'], $dtParams['start']);
            }
        }

        $kelasMahasiswa->where('kelas_mahasiswa.id_kelas', $kelasId);

        return $kelasMahasiswa->countAllResults();
    }

    /**
     * count total data
     * 
     * @param int $kelasId
     * @param array $dt_params
     * 
     * @return int
     */
    public function mahasiswaCountData(int $kelasId): int
    {
        return $this->builder('kelas_mahasiswa')
            ->where('kelas_mahasiswa.id_kelas', $kelasId)
            ->countAllResults();
    }

    /**
     * Get mahasiswa where not in selected kelas
     * 
     * @param int $kelasId
     * 
     * @return array
     */
    public function getMahasiswaNotInClass(int $kelasId): array
    {
        $mahasiswa = $this->builder('mahasiswa');
        $mahasiswa->select('
            id,
            nim,
            nama_lengkap
        ');
        $mahasiswa->whereNotIn('id', function(BaseBuilder $baseBuilder) use($kelasId) {
            return $baseBuilder->select('id_mahasiswa')
                ->from('kelas_mahasiswa')
                ->where('id_kelas', $kelasId);
        });
        $result = $mahasiswa->get()
            ->getResultObject();

        return $result;
    }

    /**
     * Register mahasiswa to a class
     * 
     * @param int $mahasiswaId
     * @param int $kelasId
     * 
     * @return void
     */
    public function addMahasiswa(int $mahasiswaId, int $kelasId)
    {
        $this->db->table('kelas_mahasiswa')
            ->insert([
                'id_kelas'      => $kelasId,
                'id_mahasiswa'  => $mahasiswaId
            ]);
    }
}
