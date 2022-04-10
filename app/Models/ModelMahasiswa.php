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

    public function search($keyword)
    {
        return $this->table('mahasiswa')->like('nama_mhs', $keyword)
            ->orLike('nim_mhs', $keyword)
            ->orLike('jurusan_mhs', $keyword);
    }

    public function EditData($data, $id)
    {
        return $this->db->table('mahasiswa')->update($data, ['id_mhs' => $id]);
    }
}
