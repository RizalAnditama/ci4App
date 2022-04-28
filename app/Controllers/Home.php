<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('role') == "admin") {
            return redirect()->to(base_url('mahasiswa'));
        } else {
            $data = [
                'title' => 'Home',
                'tampil' => 'home'
            ];
            echo view('pages/home', $data);
        }
    }

    public function welcome()
    {
        return view('welcome_message');
    }
}
