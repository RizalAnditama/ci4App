<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mhs';
    protected $useAutoIncrement = true;
    protected $returnType     = 'object';
    protected $allowedFields = [
        'nim_mhs',
        'nama_mhs',
        'TmpLahir_mhs',
        'TglLahir_mhs',
        'alamat_mhs',
        'hp_mhs',
        'jurusan_mhs',
    ];

    function TampilData()
    {
        return $this->db->table('mahasiswa')->get();
        // return (object) $this->paginate(5);
    }

    public function HapusData($id)
    {
        return $this->db->table('mahasiswa')->delete(['id_mhs' => $id]);
    }

    public function EditData($data, $id)
    {
        return $this->db->table('mahasiswa')->update($data, ['id_mhs' => $id]);
    }
}
