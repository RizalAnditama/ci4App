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
        $data = [
            'title' => 'Admin Dashboard',
        ];

        return view('pages/dashboard', $data);
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Main Dashboard',
        ];
        return view('pages/dashboard', $data);
    }
}
