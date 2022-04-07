<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelMahasiswa;

class AdminController extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') != "admin") {
            $data = [
                'title' => 'Error 403 | Access Forbiden'
            ];
            echo view('errors/http/403_access-denied', $data);
            exit;
        }
    }
    public function index()
    {
        helper('form');

        $mhs = new ModelMahasiswa();
        $data = [
            'title'     => 'Dashboard | Admin',
            'tampil'    => 'viewdatamahasiswa',
            'validation' => \Config\Services::validation(),
            'TampilData' => $mhs->TampilData()->getResultArray(),
        ];

        return view('pages/viewdatamahasiswa', $data);
    }
}
