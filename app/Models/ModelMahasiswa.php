<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mhs';
    protected $useAutoIncrement = true;
    protected $returnType     = 'object';
    // protected $useSoftDeletes = true;
    protected $allowedFields = [
        'nim_mhs',
        'nama_mhs',
        'jenis_kelamin',
        'agama_mhs',
        'pendidikan',
        'TmpLahir_mhs',
        'TglLahir_mhs',
        'alamat_mhs',
        'hp_mhs',
        'jurusan_mhs',
        'foto',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    // protected $deletedField         = 'deleted_at';

    /**
     * Buat ngesearch sesuatu berdasar keyword
     */
    public function search(string $keyword)
    {
        if (true) {
            return $this->table('mahasiswa')->like('nama_mhs', $keyword)
                ->orLike('nim_mhs', $keyword)
                ->orLike('jurusan_mhs', $keyword)
                ->orLike('TmpLahir_mhs', $keyword)
                ->orLike('TglLahir_mhs', $keyword)
                ->orLike('alamat_mhs', $keyword)
                ->orLike('hp_mhs', $keyword)
                ->orLike('foto', $keyword)
                ->orLike('jenis_kelamin', $keyword)
                ->orLike('agama_mhs', $keyword)
                ->orLike('pendidikan', $keyword)
                ->orLike('created_at', $keyword)
                ->orLike('updated_at', $keyword);
        } else {
            return session()->setFlashdata('fail_search', 'Gagal mencari data mahasiswa');
        }
    }

    /**
     * autonumber buat nambah string berdasar kode jurusan dan angka auto increment
     */
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
            $kode = '0001';
        }

        if ($jurusan == 'sejarah') {
            return 'MHS' . 'SEJ' . $kode;
        } else if ($jurusan == 'mipa') {
            return 'MHS' . 'MIP' . $kode;
        } else if ($jurusan == 'sastra') {
            return 'MHS' . 'SAS' . $kode;
        } else {
            // Akan menampilkan error jika kode jurusan tidak sesuai
            return 'MHS' . 'ERR' . $kode;
        }
    }

    /** 
     * Ngambil nama jurusan
     */
    public function getJurusan(int $id)
    {
        $query = $this->db->query("SELECT jurusan_mhs FROM mahasiswa WHERE id_mhs = '$id'");
        return $query->getRowArray();
    }

    /** 
     * Ganti format kode nim berdasar jurusan
     */
    public function changeFormat(string $nim, string $jurusan)
    {
        $kode = substr($nim, 6, 4);
        if ($jurusan == 'sejarah') {
            $nim = substr_replace($nim, 'SEJ', 3) . $kode;
        } elseif ($jurusan == 'mipa') {
            $nim = substr_replace($nim, 'MIP', 3) . $kode;
        } elseif ($jurusan == 'sastra') {
            $nim = substr_replace($nim, 'SAS', 3) . $kode;
        }

        return $nim;
    }

    /**
     * Find unique value in a table
     */
    public function findUnique(array $option, string $column_name)
    {
        $name = array_column($option, $column_name);
        $name = array_unique($name);
        return array_values($name);
    }

    /**
     * Check if value is unique
     */
    public function isUnique(string $column_name, string $value)
    {
        $value = $this->db->table('mahasiswa')->where($column_name, $value)->get()->getRowArray();
        if ($value) {
            return $value;
        }

        return true;
    }
}
