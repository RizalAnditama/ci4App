<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelMahasiswa;
use App\Models\UserModel;

class Mahasiswa extends Controller
{
    public function __construct()
    {
        $this->userModel = new UserModel();
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
            $paginate = $this->mhs->search($keyword)->paginate(5, 'mahasiswa');
            $keyword = session()->set('keyword', $keyword);
            session()->setFlashdata('home', 'Home');
            session()->markAsTempdata('keyword', 1);
        } else {
            $paginate = $this->mhs->paginate(5, 'mahasiswa');
            session()->markAsTempdata('home', 1);
            $mhs = $this->mhs;
        }

        // findall unique data in table mahasiswa and put it in option variable
        $option = $this->mhs->findAll();
        $nama = array_column($option, 'nama_mhs');
        $nama = array_unique($nama);
        $nama = array_values($nama);

        $nim = array_column($option, 'nim_mhs');
        $nim = array_unique($nim);
        $nim = array_values($nim);

        $TmpLahir = array_column($option, 'TmpLahir_mhs');
        $TmpLahir = array_unique($TmpLahir);
        $TmpLahir = array_values($TmpLahir);

        $hp = array_column($option, 'hp_mhs');
        $hp = array_unique($hp);
        $hp = array_values($hp);

        $data = [
            'title'     => 'Dashboard | Admin',
            'validation' => \Config\Services::validation(),
            'mahasiswa' => json_decode(json_encode($paginate), FALSE), //Ngubah data dari modelmahasiswa(array) ke string
            'pager'     => $mhs->pager,
            'page' => $page,
            'keyword' => $keyword,
            'nama' => $nama,
            'nim' => $nim,
            'TmpLahir' => $TmpLahir,
            'hp' => $hp,
        ];

        return view('pages/viewdatamahasiswa', $data);
    }

    public function SimpanData()
    {
        helper('form');
        $this->mhs = new ModelMahasiswa();
        if (!$this->validate([
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
            'foto' => [
                'label' => 'foto',
                'rules' => 'permit_empty|uploaded[foto]|max_size[foto,1024]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Foto harus diisi',
                    'max_size' => 'Ukuran foto maksimal 1MB',
                    'is_image' => 'Yang anda upload bukan gambar, coba lampirkan gambar',
                    'mime_in' => 'Format foto harus jpg, jpeg, atau png',
                ]
            ],
        ])) {
            $flash = [
                'head' => 'Input tidak sesuai ketentuan',
                'body' => 'Gagal menambah data',
            ];

            session()->set('id', $this->request->getVar('id'));
            session()->setFlashdata('fail_add', $flash);
            return redirect()->back()->withInput();
            // return redirect()->to('mahasiswa')->withInput()->with('validation', $validation);
        } else {
            $this->mhs = new ModelMahasiswa();
            $foto = $this->request->getFile('foto');
            if ($foto->getSize() > 0) {
                $fileName = $this->userModel->timestampFile($foto->getName());
                if ($foto->move("images/mahasiswa", $fileName)) {
                    $foto = base_url() . '/' . 'images/mahasiswa/' . $fileName;
                }
            } else {
                $foto = null;
            }

            $data = [
                'nim_mhs' => $this->mhs->autonumber($this->request->getVar('jurusan')), // Pake autonumber dari model buat ngenomorinnya
                'nama_mhs' => $this->request->getVar('nama'),
                'TmpLahir_mhs' => $this->request->getVar('TmpLahir'),
                'TglLahir_mhs' => $this->request->getVar('TglLahir'),
                'alamat_mhs' => $this->request->getVar('alamat'),
                'hp_mhs' => $this->request->getVar('telepon'),
                'jurusan_mhs' => $this->request->getVar('jurusan'),
                'foto' => $foto,
            ];

            // Ngambil data terakhir dari database (untuk nim dan id)
            $nim = $this->mhs->autonumber($this->request->getVar('jurusan'));
            $id = $this->mhs->insert($data);

            session()->set('nama', $this->request->getVar('nama'));
            session()->setFlashdata('success_add', 'Data Berhasil Diinput');

            return redirect()->back()->with('id', $id)->with('nim', $nim);
        }
    }

    public function UpdateData()
    {
        $nim = session()->set('nim');
        if ($nim == $this->request->getVar('nim_edit')) {
            $hp_unik = 'required|numeric|is_unique[mahasiswa.hp_mhs]|min_length[7]|max_length[15]';
        } else {
            $hp_unik = 'required|numeric|min_length[7]|max_length[15]';
        }
        if (!$this->validate([
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
            'foto_edit' => [
                'label' => 'foto',
                'rules' => 'permit_empty|uploaded[foto]|max_size[foto,1024]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Foto harus diisi',
                    'max_size' => 'Ukuran foto maksimal 1MB',
                    'is_image' => 'Yang anda upload bukan gambar, coba lampirkan gambar',
                    'mime_in' => 'Format foto harus jpg, jpeg, atau png',
                ]
            ],
        ])) {
            $id = $this->request->getVar('id');
            $flash = [
                'head' => 'Input tidak sesuai ketentuan',
                'body' => 'Gagal mengedit data',
            ];

            $validation = \Config\Services::validation();

            session()->set('id', $this->request->getVar('id'));
            session()->set('nama', json_decode(json_encode(($this->mhs->query("SELECT nama_mhs FROM mahasiswa WHERE id_mhs = '$id'")->getRowArray()['nama_mhs']))), FALSE);
            session()->setFlashdata('fail_edit', $flash);
            return redirect()->back()->withInput();
        } else {
            $id = $this->request->getVar('id');

            $foto = $this->request->getFile('foto_edit');
            if ($foto->getSize() > 0) {
                $fileName = $this->userModel->timestampFile($foto->getName());
                if ($foto->move("images/mahasiswa", $fileName)) {
                    $foto = base_url() . '/' . 'images/mahasiswa/' . $fileName;
                }
            } else {
                $foto = null;
            }

            $data = [
                'nama_mhs' => $this->request->getVar('nama_edit'),
                'TmpLahir_mhs' => $this->request->getVar('TmpLahir_edit'),
                'TglLahir_mhs' => $this->request->getVar('TglLahir_edit'),
                'alamat_mhs' => $this->request->getVar('alamat_edit'),
                'hp_mhs' => $this->request->getVar('telepon_edit'),
                'jurusan_mhs' => $this->request->getVar('jurusan_edit'),
                'foto' => $foto,
            ];
            // get the original value of jurusan_mhs and store it in $jurusan
            $jurusan_edit = $this->request->getVar('jurusan_edit');
            $jurusan = $this->mhs->getJurusan($id);

            $this->mhs = new ModelMahasiswa();

            if ($jurusan != $jurusan_edit) {
                $nim = $this->mhs->changeFormat($this->request->getVar('nim_edit'), $jurusan_edit);
                $data['nim_mhs'] = $nim;
            }
            $edit = $this->mhs->update($id, $data);

            if ($edit) {
                session()->set('id', $this->request->getVar('id'));
                session()->set('nim', (string) $data['nim_mhs']);
                session()->set('nama', $this->request->getVar('nama_edit'));
                session()->setFlashdata('success_edit', 'Data Berhasil Diedit');
                return redirect()->back();
            }
        }
    }

    public function hapus($id)
    {
        $mhs = new ModelMahasiswa();
        $mhs->delete($id);
        session()->setFlashdata('deleted', 'Data berhasil dihapus');
        return redirect()->to('mahasiswa')->withInput();
    }
}
