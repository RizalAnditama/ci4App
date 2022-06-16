<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ModelMahasiswa;
use App\Models\UserModel;

class Mahasiswa extends BaseController
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

        // //findall unique data in table mahasiswa and put it in option variable
        // $option = $this->mhs->findAll();
        // $nama = $this->mhs->findUnique($option, 'nama_mhs');
        // $nim = $this->mhs->findUnique($option, 'nim_mhs');
        // $TmpLahir = $this->mhs->findUnique($option, 'TmpLahir_mhs');
        // $hp = $this->mhs->findUnique($option, 'hp_mhs');

        $data = [
            'title'     => 'Dashboard | Admin',
            'validation' => \Config\Services::validation(),
            'mahasiswa' => json_decode(json_encode($paginate), FALSE), //Ngubah data dari modelmahasiswa(array) ke string
            'pager'     => $mhs->pager,
            'page' => $page,
            'keyword' => $keyword,
            // 'nama' => $nama,
            // 'nim' => $nim,
            // 'TmpLahir' => $TmpLahir,
            // 'hp' => $hp,
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
            'jenkel' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field Jenis Kelamin harus diisi',
                    'alpha_space' => 'Field Jenis Kelamin hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field Jenis Kelamin adalah 255 karakter'
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
            'agama' => [
                'label' => 'agama',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field agama harus diisi',
                    'alpha_space' => 'Field agama hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field agama adalah 255 karakter'
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
            'pendidikan' => [
                'label' => 'pendidikan',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Pendidikan harus diisi',
                    'max_length' => 'maksimum karakter untuk field Pendidikan adalah 255 karakter'
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
                $foto = base_url() . '/' . 'images/mahasiswa/' . 'default-profile.jpg';
            }

            $data = [
                'nim_mhs' => $this->mhs->autonumber($this->request->getVar('jurusan')), // Pake autonumber dari model buat ngenomorinnya
                'nama_mhs' => $this->request->getVar('nama'),
                'jenis_kelamin' => $this->request->getVar('jenkel'),
                'TmpLahir_mhs' => $this->request->getVar('TmpLahir'),
                'TglLahir_mhs' => $this->request->getVar('TglLahir'),
                'agama_mhs' => $this->request->getVar('agama'),
                'alamat_mhs' => $this->request->getVar('alamat'),
                'hp_mhs' => $this->request->getVar('telepon'),
                'pendidikan' => $this->request->getVar('pendidikan'),
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
            'jenkel_edit' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field Jenis Kelamin harus diisi',
                    'alpha_space' => 'Field Jenis Kelamin hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field Jenis Kelamin adalah 255 karakter'
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
            'agama_edit' => [
                'label' => 'Agama',
                'rules' => 'required|alpha_space|max_length[255]',
                'errors' => [
                    'required' => 'Field Agama harus diisi',
                    'alpha_space' => 'Field Agama hanya boleh berisi huruf dan spasi',
                    'max_length' => 'maksimum karakter untuk field Field Agama adalah 255 karakter'
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
            'pendidikan_edit' => [
                'label' => 'pendidikan',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Pendidikan harus diisi',
                    'max_length' => 'maksimum karakter untuk field Pendidikan adalah 255 karakter'
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

                    $data['foto'] = $foto;
                    $edit_foto = $this->mhs->update($id, $data);
                }
            }

            $data = [
                'nama_mhs' => $this->request->getVar('nama_edit'),
                'jenis_kelamin' => $this->request->getVar('jenkel_edit'),
                'TmpLahir_mhs' => $this->request->getVar('TmpLahir_edit'),
                'TglLahir_mhs' => $this->request->getVar('TglLahir_edit'),
                'agama_mhs' => $this->request->getVar('agama_edit'),
                'alamat_mhs' => $this->request->getVar('alamat_edit'),
                'hp_mhs' => $this->request->getVar('telepon_edit'),
                'jurusan_mhs' => $this->request->getVar('jurusan_edit'),
                'pendidikan' => $this->request->getVar('pendidikan_edit'),
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

            if ($edit || $edit_foto) {
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

    public function exportExcel()
    {
        $mhs = new ModelMahasiswa();
        $dataMhs = $mhs->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'No')
            ->setCellValue('B1', 'NIM')
            ->setCellValue('C1', 'Nama')
            ->setCellValue('D1', 'Jurusan')
            ->setCellValue('E1', 'Jenis Kelamin')
            ->setCellValue('F1', 'Agama')
            ->setCellValue('G1', 'Alamat')
            ->setCellValue('H1', 'No. HP')
            ->setCellValue('I1', 'Pendidikan')
            ->setCellValue('J1', 'Tanggal Lahir')
            ->setCellValue('K1', 'Tempat Lahir');

        $column = 2;
        // tulis data mhs ke cell
        foreach ($dataMhs as $data) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, ($column - 1))
                ->setCellValue('B' . $column, $data->nim_mhs)
                ->setCellValue('C' . $column, $data->nama_mhs)
                ->setCellValue('D' . $column, $data->jurusan_mhs)
                ->setCellValue('E' . $column, ($data->jenis_kelamin === 'l') ? 'Laki-laki' : 'Perempuan')
                ->setCellValue('F' . $column, $data->agama_mhs)
                ->setCellValue('G' . $column, $data->alamat_mhs)
                ->setCellValue('H' . $column, $data->hp_mhs)
                ->setCellValue('I' . $column, $data->pendidikan)
                ->setCellValue('J' . $column, $data->TglLahir_mhs)
                ->setCellValue('K' . $column, $data->TmpLahir_mhs);
            $column++;
        }

        // set title to bold
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        // Set horizontal
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H1:K' . ($column - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // Fill title with background color
        $spreadsheet
            ->getActiveSheet()
            ->getStyle('A1:K1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FAFA33');
        // change number format
        $spreadsheet->getActiveSheet()
            ->getStyle('H2:H' . ($column - 1))
            ->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
        // Borders the table
        $sheet->getStyle('A1:K' . ($column - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()
            ->setARGB('000000');

        // set auto size for column
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);

        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Mahasiswa';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    /**
     * Turns excel into values for databases
     */
    public function importExcel()
    {
        // Validation
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,1024]|ext_in[file,csv],'
        ]);
        if (!$input) { // Not valid
            $data['validation'] = $this->validator;
            return view('users/index', $data);
        } else { // Valid
            if ($file = $this->request->getFile('file')) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Get random file name
                    $newName = $file->getRandomName();
                    // Store file in public/csvfile/ folder
                    $file->move('../public/csvfile', $newName);
                    // Reading file
                    $file = fopen("../public/csvfile/" . $newName, "r");
                    $i = 0;
                    $numberOfFields = 4; // Total number of fields
                    $importData_arr = array();
                    // Initialize $importData_arr Array
                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata);
                        // Skip first row & check number of fields
                        if (
                            $i > 0 && $num == $numberOfFields
                        ) {
                            // Key names are the insert table field names - name, email, city, and status
                            $importData_arr[$i]['name'] = $filedata[0];
                            $importData_arr[$i]['email'] = $filedata[1];
                            $importData_arr[$i]['city'] = $filedata[2];
                            $importData_arr[$i]['status'] = $filedata[3];
                        }
                        $i++;
                    }
                    fclose($file);
                    // Insert data
                    $count = 0;
                    foreach ($importData_arr as $userdata) {
                        $users = new ModelMahasiswa();
                        // Check record
                        $checkrecord = $users->where('email', $userdata['email'])->countAllResults();
                        if ($checkrecord == 0) {
                            ## Insert Record
                            if ($users->insert($userdata)) {
                                $count++;
                            }
                        }
                    }
                    // Set Session
                    session()->setFlashdata('message', $count . ' Record inserted successfully!');
                } else {
                    // Set Session
                    session()->setFlashdata('message', 'File not imported.');
                }
            } else {
                // Set Session
                session()->setFlashdata('message', 'File not imported.');
            }
        }
        return redirect()->route('/');
    }
}
