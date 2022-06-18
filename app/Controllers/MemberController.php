<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class MemberController extends BaseController
{
    public function __construct()
    {
        if (session()->get('role') != "member") {
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
            'title'     => 'Dashboard',
        ];

        return view('pages/dashboard', $data);
    }
}
