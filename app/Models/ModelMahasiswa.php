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

    // autonumber buat nambah string berdasar kode jurusan dan angka auto increment
    public function autonumber($jurusan)
    {
        $query = $this->db->query("SELECT MAX(RIGHT(nim_mhs,4)) AS kode FROM mahasiswa");
        $kode = "";
        if ($query->getRowArray()) {
            foreach ($query->getResult() as $k) {
                $tmp = ((int) $k->kode) + 1;
                $kode = sprintf("%04s", $tmp);
            }
        } else {
            $kode = "0001";
        }

        if ($jurusan == 'sejarah') {
            return "MHS" . "SEJ" . $kode;
        } else if ($jurusan == 'mipa') {
            return "MHS" . "MIPA" . $kode;
        } else if ($jurusan == 'sastra') {
            return "MHS" . "SAS" . $kode;
        } else {
            return "MHS" . $kode;
        }
    }

    public function autonumber_edit($jurusan)
    {
        $query = $this->db->query("SELECT MAX(RIGHT(nim_mhs,4)) AS kode FROM mahasiswa");
        $kode = "";
        if ($query->getRowArray()) {
            foreach ($query->getResult() as $k) {
                $tmp = ((int) $k->kode);
                $kode = sprintf("%04s", $tmp);
            }
        } else {
            $kode = "0001";
        }

        if ($jurusan == 'sejarah') {
            return "MHS" . "SEJ" . $kode;
        } else if ($jurusan == 'mipa') {
            return "MHS" . "MIPA" . $kode;
        } else if ($jurusan == 'sastra') {
            return "MHS" . "SAS" . $kode;
        } else {
            return "MHS" . $kode;
        }
    }
}
