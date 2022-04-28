<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Main Dashboard',
        ];
        return view('pages/dashboard', $data);
    }
}
