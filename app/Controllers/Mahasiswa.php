<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelMahasiswa;

class Mahasiswa extends Controller
{
    public function __construct()
    {
        $this->mhs = new ModelMahasiswa();
        if (session()->get('role') != "admin") {
            $data = [
                'title' => 'Error 403 | Access Forbiden'
            ];
            echo view('errors/http/403_access-denied', $data);
            exit;
        } // Untuk memastikan kalo yang ngakses kontroller mahasiswa itu cuman admin
    }

    public function index()
    {
        session();
        helper('form');
        $this->mhs = new ModelMahasiswa();

        $page = $this->request->getVar('page_mahasiswa') ? $this->request->getVar('page_mahasiswa') : 1;
        $keyword = $this->request->getVar('keyword');

        if ($keyword) {
            $mhs = $this->mhs->search($keyword);
        } else {
            session()->setFlashdata('fail_search', 'Gagal mencari data mahasiswa');
            $mhs = $this->mhs;
        }   

        $data = [
            'title'     => 'Dashboard | Admin',
            'tampil'    => 'viewdatamahasiswa',
            'validation' => \Config\Services::validation(),
            'mahasiswa' => json_decode(json_encode($mhs->paginate(5, 'mahasiswa')), FALSE), //Ngubah data dari modelmahasiswa(array) ke object
            'pager'     => $mhs->pager,
            'page' => $page,
            'keyword' => $keyword,
        ];

        echo view('pages/viewdatamahasiswa', $data);
    }

    public function SimpanData()
    {
        helper('form');
        $this->mhs = new ModelMahasiswa();
        if (!$this->validate([
            'nim' => [
                'label' => 'nim',
                'rules' => 'required|numeric|max_length[7]|is_unique[mahasiswa.nim_mhs]',
                'errors' => [
                    'required' => 'NIM harus diisi',
                    'numeric' => 'NIM harus berupa angka',
                    'max_length' => 'maksimum karakter untuk field NIM adalah 7 karakter',
                    'is_unique' => 'NIM sudah terdaftar',
                ]
            ],
            'nama' => [
                'label' => 'nama',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field Nama harus diisi',
                    'alpha_space' => 'Field Nama hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field Nama adalah 255 karakter'
                ]
            ],
            'TmpLahir' => [
                'label' => 'TmpLahir',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field tempat lahir harus diisi',
                    'alpha_space' => 'Field tempat lahir hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field tempat lahir adalah 255 karakter'
                ]
            ],
            'TglLahir' => [
                'label' => 'TglLahir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Lahir harus diisi',
                ]
            ],
            'alamat' => [
                'label' => 'alamat',
                'rules' => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'minimum karakter untuk Alamat adalah 5 karakter',
                    'max_length' => 'maksimum karakter untuk field Alamat adalah 255 karakter'
                ]
            ],
            'telepon' => [
                'label' => 'telepon',
                'rules' => 'required|numeric|is_unique[mahasiswa.hp_mhs]|min_length[7]|max_length[15]',
                'errors' => [
                    'required' => 'No Telepon harus diisi',
                    'numeric' => 'No Telepon harus berupa angka',
                    'is_unique' => 'Nomor HP sudah terdaftar, mohon coba lagi',
                    'min_length' => 'minimum karakter untuk No Telepon adalah 7 karakter',
                    'max_length' => 'maksimum karakter untuk No Telepon adalah 15 karakter'
                ]
            ],
            'jurusan' => [
                'label' => 'jurusan',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Jurusan harus diisi',
                    'alpha_space' => 'Jurusan hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Jurusan adalah 255 karakter'
                ]
            ],
        ])) {
            $flash = [
                'head' => 'Input tidak sesuai ketentuan',
                'body' => 'Gagal menambah data',
            ];

            $validation = \Config\Services::validation();

            session()->set('id', $this->request->getPost('id'));
            session()->setFlashdata('fail_add', $flash);
            return redirect()->back()->withInput();
            // return redirect()->to('mahasiswa')->withInput()->with('validation', $validation);
        } else {
            $this->mhs = new ModelMahasiswa();
            $data = [
                'nim_mhs' => $this->request->getPost('nim'),
                'nama_mhs' => $this->request->getPost('nama'),
                'TmpLahir_mhs' => $this->request->getPost('TmpLahir'),
                'TglLahir_mhs' => $this->request->getPost('TglLahir'),
                'alamat_mhs' => $this->request->getPost('alamat'),
                'hp_mhs' => $this->request->getPost('telepon'),
                'jurusan_mhs' => $this->request->getPost('jurusan'),
            ];

            $id = $this->mhs->insert($data);
            session()->set('nama', $this->request->getPost('nama'));
            session()->set('nim', $this->request->getPost('nim'));
            session()->setFlashdata('success_add', 'Data Berhasil Diinput');
            return redirect()->to('/mahasiswa')->with('id', $id);
        }
    }

    public function UpdateData()
    {
        $nim = session()->set('nim');
        if ($nim == $this->request->getPost('nim_edit')) {
            $nim_unik = 'required|numeric|is_unique[mahasiswa.nim_mhs]';
            $hp_unik = 'required|numeric|is_unique[mahasiswa.hp_mhs]|min_length[7]|max_length[15]';
        } else {
            $nim_unik = 'required|numeric';
            $hp_unik = 'required|numeric|min_length[7]|max_length[15]';
        }
        if (!$this->validate([
            'nim_edit' => [
                'label' => 'nim',
                'rules' => $nim_unik,
                'errors' => [
                    'required' => 'NIM harus diisi',
                    'numeric' => 'NIM harus berupa angka',
                    'max_length' => 'maksimum karakter untuk field NIM adalah 7 karakter',

                ]
            ],
            'telepon_edit' => [
                'label' => 'HP',
                'rules' => $hp_unik,
                'errors' => [
                    'required' => 'No Telepon harus diisi',
                    'numeric' => 'No Telepon harus berupa angka',
                    'is_unique' => 'Nomor HP sudah terdaftar, mohon coba lagi',
                    'min_length' => 'minimum karakter untuk No Telepon adalah 7 karakter',
                    'max_length' => 'maksimum karakter untuk No Telepon adalah 15 karakter'
                ]
            ],
            'nama_edit' => [
                'label' => 'nama',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field Nama harus diisi',
                    'alpha_space' => 'Field Nama hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field Nama adalah 255 karakter'
                ]
            ],
            'TmpLahir_edit' => [
                'label' => 'TmpLahir',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field tempat lahir harus diisi',
                    'alpha_space' => 'Field tempat lahir hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field tempat lahir adalah 255 karakter'
                ]
            ],
            'TglLahir_edit' => [
                'label' => 'TglLahir',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Lahir harus diisi',
                ]
            ],
            'alamat_edit' => [
                'label' => 'alamat',
                'rules' => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'minimum karakter untuk Alamat adalah 5 karakter',
                    'max_length' => 'maksimum karakter untuk field Alamat adalah 255 karakter'
                ]
            ],
            'jurusan_edit' => [
                'label' => 'jurusan',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Jurusan harus diisi',
                    'alpha_space' => 'Jurusan hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Jurusan adalah 255 karakter'
                ]
            ],
        ])) {
            $flash = [
                'head' => 'Input tidak sesuai ketentuan',
                'body' => 'Gagal mengedit data',
            ];

            $validation = \Config\Services::validation();

            session()->set('id', $this->request->getPost('id'));
            session()->setFlashdata('fail_edit', $flash);
            return redirect()->back()->withInput();
        } else {
            $id = $this->request->getPost('id');
            $data = [
                'nama_mhs' => $this->request->getPost('nama_edit'),
                'TmpLahir_mhs' => $this->request->getPost('TmpLahir_edit'),
                'TglLahir_mhs' => $this->request->getPost('TglLahir_edit'),
                'alamat_mhs' => $this->request->getPost('alamat_edit'),
                'hp_mhs' => $this->request->getPost('telepon_edit'),
                'jurusan_mhs' => $this->request->getPost('jurusan_edit'),
            ];

            $this->mhs = new ModelMahasiswa();

            $edit = $this->mhs->EditData($data, $id);

            if ($edit) {
                session()->set('id', $this->request->getPost('id'));
                session()->set('nim', $this->request->getPost('nim_edit'));
                session()->set('nama', $this->request->getPost('nama_edit'));
                session()->setFlashdata('success_edit', 'Data Berhasil Diedit');
                return redirect()->to('Mahasiswa');
            }
        }
    }

    public function hapus($id)
    {
        $mhs = new ModelMahasiswa();
        $mhs->delete($id);
        session()->setFlashdata('deleted', 'Data berhasil dihapus');
        return redirect()->back()->withInput();
    }

    // public function edit()
    // {
    //     $this->mhs = new ModelMahasiswa();
    //     $nim = $this->request->getPost('nim_mhs');
    //     $data = [
    //         'nama_mhs' => $this->request->getPost('nama'),
    //         'TmpLahir_mhs' => $this->request->getPost('TmpLahir'),
    //         'TglLahir_mhs' => $this->request->getPost('TglLahir'),
    //         'alamat_mhs' => $this->request->getPost('alamat'),
    //         'hp_mhs' => $this->request->getPost('telepon'),
    //         'jurusan_mhs' => $this->request->getPost('jurusan'),
    //     ];
    //     $this->mhs->EditData($data, $this->mhs);
    //     return redirect()->to('/mahasiswa');
    // }


    // $data = [
    //     'id_edit', $row['id_mhs'],
    //     'nim_edit', $row['nim_mhs'],
    //     'nama_edit', $row['nama_mhs'],
    //     'TmpLahir_edit', $row['TmpLahir_mhs'],
    //     'TglLahir_edit', $row['TglLahir_mhs'],
    //     'alamat_edit', $row['alamat_mhs'],
    //     'hp_edit', $row['hp_mhs'],
    //     'jurusan_edit', $row['jurusan_mhs'],
    // ];

    // session()->set($data);
}
