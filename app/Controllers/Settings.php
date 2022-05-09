<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use PhpParser\Node\Expr\Isset_;

class Settings extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();

        $this->session = session();
        $this->session->start();
        if (!$this->session->get('username')) {
            return redirect()->to('/login');
        }
    }

    public function index()
    {
        helper(['form']);
        $data = [
            'title' => 'Settings',
        ];
        return view('pages/settings', $data);
    }

    public function profile()
    {
        helper(['form']);
        $data = [
            'title' => 'My Profile',
            'errors' => [],
            'validation' => $this->validator,
            'profile_pic' => base_url() . '/' . $this->userModel->getProfilePic(session()->get('id_user')),
        ];

        if ($this->request->getMethod() == 'post') {
            $username = session()->get('username');
            $hp = session()->get('phone_no');
            if ($username != $this->request->getVar('username')) {
                $username = 'required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[50]';
            } else {
                $username = 'required|alpha_numeric|min_length[3]|max_length[50]';
            }

            if ($hp != $this->request->getVar('phone_no')) {
                $hp = 'required|is_unique[users.phone_no]|numeric|min_length[7]|max_length[15]';
            } else {
                $hp = 'required|numeric|min_length[7]|max_length[15]';
            }

            if (null !== ($this->request->getVar('newPassword'))) {
                if (null != ($this->request->getVar('newPassword') || $this->request->getVar('oldPassword'))) {
                    $oldPassword = 'required|checkOldPassword[oldPassword]';
                    $newPassword = 'required|min_length[8]|max_length[255]|is_unique[users.password]';
                    $passwordConfirm = 'required|matches[newPassword]';
                } else {
                    $oldPassword = 'permit_empty';
                    $newPassword = 'permit_empty';
                    $passwordConfirm = 'permit_empty';
                }
            } else {
                $oldPassword = 'permit_empty';
                $newPassword = 'permit_empty';
                $passwordConfirm = 'permit_empty';
            }

            if ($this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => $username,
                    'errors' => [
                        'required' => 'Field Username harus diisi',
                        'is_unique' => 'Username sudah dipakai',
                        'alpha_numeric' => 'Field Username hanya boleh berisi huruf dan angka',
                        'min_length' => 'Minimum karakter untuk Field Username adalah 3 karakter',
                        'max_length' => 'Maksimum karakter untuk Field Username adalah 50 karakter'
                    ]
                ],
                'name' => [
                    'label' => 'name',
                    'rules' => 'required|alpha_space|max_length[255]|min_length[3]',
                    'errors' => [
                        'required' => 'Field Nama harus diisi',
                        'alpha_space' => 'Field Nama hanya boleh berisi Huruf dan spasi',
                        'max_length' => 'Maksimum karakter untuk Field Nama adalah 255 karakter',
                        'min_length' => 'Minimum karakter untuk Field Nama adalah 3 karakter'
                    ]
                ],
                'phone_no' => [
                    'label' => 'Phone Number',
                    'rules' => $hp,
                    'errors' => [
                        'is_unique' => 'Nomor hp sudah dipakai',
                        'required' => 'Field Phone No harus diisi',
                        'numeric' => 'Field Phone No hanya boleh berisi angka',
                        'min_length' => 'Maksimum digit untuk Field Phone No adalah 7 karakter',
                        'max_length' => 'Maksimum digit untuk Field Phone No adalah 15 karakter'
                    ]
                ],
                'oldPassword' => [
                    'label' => 'Old Password',
                    'rules' => $oldPassword,
                    'errors' => [
                        'required' => 'Field Password Lama harus diisi',
                        'checkOldPassword' => 'Password lama tidak sesuai',
                    ]
                ],
                'newPassword' => [
                    'label' => 'New Password',
                    'rules' => $newPassword,
                    'errors' => [
                        'required' => 'Field New Password harus diisi',
                        'min_length' => 'Minimum karakter untuk Field New Password adalah 8 karakter',
                        'max_length' => 'Maksimum karakter untuk Field New Password adalah 255 karakter',
                        'is_unique' => 'Password harus berbeda dari yang lama'
                    ]
                ],
                'passwordConfirm' => [
                    'label' => 'Confirm Password',
                    'rules' => $passwordConfirm,
                    'errors' => [
                        'required' => 'Password harus di konfirmasi',
                        'matches' => 'Password konfirmasi harus sama',
                    ]
                ],
            ])) {
                if (null !== ($this->request->getPost('newPassword'))) {
                    // check old password
                    // if new password is the same as old password, then do not update password
                    if ($this->request->getPost('newPassword') != $this->request->getPost('oldPassword')) {
                        $newPassword = $this->request->getPost('newPassword');
                        $data = [
                            'password' => $newPassword,
                        ];
                        $this->userModel->update(session()->get('id_user'), $data);
                    }
                }

                $data = [
                    'username' => $this->request->getPost('username'),
                    'name' => $this->request->getPost('name'),
                    'phone_no' => $this->request->getPost('phone_no'),
                ];

                session()->set($data);

                $this->userModel->update(['id' => session()->get('id_user')], $data);
                session()->setFlashdata('success', 'Profile berhasil diubah');
                return redirect()->back();
            } else {
                $errors = $this->validator->getErrors();
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        }
        return view('pages/profile', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}
