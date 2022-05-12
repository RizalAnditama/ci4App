<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
        $this->email = \Config\Services::email();
    }

    public function error404()
    {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    public function login()
    {
        helper('form');
        // Data awal
        $data = [
            'title' => 'Login',

            'username' => '',
            'name' => '',
            'phone_no' => '',
            'user' => '',
            'email' => '',
            'password' => '',

            'remember' => '',

        ];

        // Check if $user is a username or email
        $userEmail = $this->request->getVar('user');
        $userEmail = filter_var($userEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($this->request->getMethod() == 'post') {
            $userVal = $userEmail;
            if ($userVal == 'email') {
                $user = 'required|min_length[6]|max_length[50]|valid_email|is_exist[email]';
                $err = [
                    'required' => 'Field Email harus diisi',
                    'valid_email' => 'Email harus valid "(Memakai @ dan .com)"',
                    'is_exist' => 'Email tidak terdaftar',
                    'min_length' => 'Minimum karakter untuk Field Email adalah 6 karakter',
                    'max_length' => 'Maksimum karakter untuk Field Email adalah 50 karakter'
                ];
                $pass = [
                    'required' => 'Field password harus diisi',
                    'validateUser' => 'Email atau password tidak cocok',
                    'min_length' => 'Minimum karakter untuk Field password adalah 8 karakter',
                    'max_length' => 'Maksimum karakter untuk Field password adalah 255 karakter'
                ];
            } else {
                $user = 'required|min_length[3]|max_length[50]|is_exist[username]';
                $err = [
                    'required' => 'Field Username harus diisi',
                    'is_exist' => 'Username tidak terdaftar',
                    'alpha_numeric' => 'Field Username hanya boleh berisi huruf dan angka',
                    'min_length' => 'Minimum karakter untuk Field Username adalah 3 karakter',
                    'max_length' => 'Maksimum karakter untuk Field Username adalah 50 karakter'
                ];
                $pass = [
                    'required' => 'Field password harus diisi',
                    'validateUser' => 'Username atau password tidak cocok',
                    'min_length' => 'Minimum karakter untuk Field password adalah 8 karakter',
                    'max_length' => 'Maksimum karakter untuk Field password adalah 255 karakter'
                ];
            }

            $rules = [
                'user' => $user,
                'password' => 'required|min_length[8]|max_length[255]|validateUser[email,password]',
                // 'name' => 'required|alpha_numeric_space|min_length[2]|max_length[255]',
                // 'phone' => 'required|numeric|min_length[7]|max_length[15]',
            ];

            $errors = [
                'user' => $err,
                'password' => $pass,
            ];

            if ($userEmail == 'email') {
                $email = $this->request->getVar('user');
            } else {
                $email = '';
            }

            // Validate if user input is valid
            if (!$this->validate($rules, $errors)) {
                $data = [
                    'validation' => $this->validator,
                    'title' => 'Login',
                    'user' => $this->request->getPost('user'),
                    'password' => $this->request->getPost('password'),
                    'email' => $email,
                    'emailforgot' => $email,

                    'remember' => '',
                ];

                return view('pages/login', $data);
            } else {
                $model = new UserModel();

                // Let user login via email or username
                $user = $model->where('email', $this->request->getVar('user'))->orwhere('username', $this->request->getVar('user'))->first();

                // Storing session values
                $this->setUserSession($user);
                session()->setFlashdata('ye', 'active');
                session()->markAsTempdata('ye', 3);

                // Redirecting to dashboard after login
                if ($user['role'] == "admin") {
                    return redirect()->to(base_url('admin'));
                } elseif ($user['role'] == "member") {
                    return redirect()->to(base_url('member'));
                }
            }
        }
        return view('pages/login', $data);
    }

    private function setUserSession($user)
    {
        $data = [
            'id_user' => $user['id'],
            'username' => $user['username'],
            'name' => $user['name'],
            'phone_no' => $user['phone_no'],
            'email' => $user['email'],
            'isLoggedIn' => true,
            'role' => $user['role'],
        ];

        session()->set($data);
        return true;
    }

    public function sendEmail($user)
    {
        $this->email->setFrom('anditamarizal@gmail.com', 'Rizal Codeigniter');
        $this->email->setTo($user['email']);

        $this->email->setSubject('Reset Password');
        $this->email->setMessage(
            '<h3>Hi, ' . $user['username'] . '</h3> 
            Click here to reset your password: <br> 
            <a class="btn btn-primary" href=">' . base_url('reset-password/' . $user['token']) .
                '"></a><br>or click the link below and use the token <br>' .
                '<a href="' . base_url('reset-password') . '/' . $user['uuid'] . '">Reset Password</a><br>' .
                '<br>Token: <h3>' . $user['token'] .  '</h3>'
        );

        if ($this->email->send()) {
            session()->set('token', $user['token']);
            return true;
        } else {
            echo $this->email->printDebugger();
            return false;
        }
    }

    public function resetPassword($slug)
    {
        $model = new UserModel();
        $data = [
            'title' => 'Reset Password',
            'slug' => $slug,
        ];

        $user = $model->where('uuid', $slug)->first();
        if (null != $user) {
            if ($user['token_expire'] > date('Y-m-d H:i:s')) {
                if ($this->request->getMethod() == 'post') {
                    $rules = [
                        'token' => 'required|is_token_expired[token]',
                        'user' => 'required|valid_email|is_exist[user]|is_token_in_email[user,token]',
                        'password' => 'required|min_length[8]|max_length[255]',
                        'pass_confirm' => 'required|matches[password]',
                    ];
                    $errors = [
                        'token' => [
                            'required' => 'Token tidak boleh kosong',
                            'is_token' => 'Token tidak cocok',
                            'is_token_expired' => 'Token sudah kadaluarsa',
                        ],
                        'user' => [
                            'required' => 'Email tidak boleh kosong',
                            'valid_email' => 'Email tidak valid',
                            'is_exist' => 'Email tidak terdaftar',
                            'is_token_in_email' => 'Token tidak cocok dengan email. Silahkan cek email anda',
                        ],
                        'password' => [
                            'required' => 'Password tidak boleh kosong',
                            'min_length' => 'Minimum karakter untuk Field password adalah 8 karakter',
                            'max_length' => 'Maksimum karakter untuk Field password adalah 255 karakter',
                        ],
                        'pass_confirm' => [
                            'required' => 'Konfirmasi password tidak boleh kosong',
                            'matches' => 'Konfirmasi password tidak cocok',
                        ],
                    ];

                    if (!$this->validate($rules, $errors)) {
                        $data = [
                            'validation' => $this->validator,
                            'title' => 'Reset Password',
                            'slug' => $slug,
                            'token' => $this->request->getPost('token'),
                            'user' => $this->request->getPost('user'),
                        ];
                    } else {
                        $user = $model->where('email', $this->request->getVar('user'))->first();

                        $data = [
                            'token' => null,
                            'token_expire' => null,
                            'password' => $this->request->getVar('password'),
                        ];

                        $model->update($user['id'], $data);

                        session()->destroy();
                        session()->set('success', 'Password berhasil diubah');
                        return redirect()->to(base_url('login'));
                    }
                }
            } else {
                session()->setTempdata('expired', 'expired', 3);
            }
        } else {
            session()->setTempdata('not-found', 'not found', 3);
        }
        return view('pages/reset-password', $data);
    }

    public function ForgotPassword()
    {
        $data = [
            'title' => 'Forgot Your Password ?',
        ];

        // validate email
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'email' => 'required|valid_email',
            ];

            $errors = [
                'email' => [
                    'required' => 'Field Email harus diisi',
                    'valid_email' => 'Email harus valid "(Memakai @ dan .com)"',
                ],
            ];

            if (!$this->validate($rules, $errors)) {
                $data = [
                    'title' => 'Forgot Your Password ? (Unfinished)',
                    'validation' => $this->validator,
                    'email' => $this->request->getVar('email'),
                ];

                return view('pages/forgot-password', $data);
            } else {
                $model = new UserModel();
                $user = $model->where('email', $this->request->getVar('email'))->first();

                if ($user) {
                    $this->sendEmail([
                        'email' => $this->request->getVar('email'),
                        'username' => $model->getUsername($this->request->getVar('email'))['username'],
                        'uuid' => $model->getUuid($this->request->getVar('email')),
                        'token' => $model->createToken($this->request->getVar('email')),
                    ]);

                    session()->setFlashdata('success', 'Silahkan cek email anda untuk melakukan reset password<br><b>Link akan dihapus dalam 15 menit</b>');
                    session()->markAsTempdata('success', 1);

                    $data = [
                        'title' => 'Forgot Your Password ? (Unfinished)',
                        'email' => $this->request->getVar('email'),
                    ];
                } else {
                    $data = [
                        'title' => 'Forgot Your Password ? (Unfinished)',
                        'email' => $this->request->getVar('email'),
                    ];
                    session()->setFlashdata('error', 'Email tidak terdaftar');
                    session()->markAsTempdata('error', 1);
                    return view('pages/forgot-password', $data);
                }
            }
        }

        return view('pages/forgot-password', $data);
    }

    public function register()
    {
        $data = [
            'title' => 'Register',

            'username' => '',
            'name' => '',
            'phone_no' => '',
            'email' => '',
            'password' => '',
            'password_confirm' => '',

            'remember' => '',

        ];

        if ($this->request->getMethod() == 'post') {
            $admin = $this->request->getVar('admin');
            if (null != $admin) {
                $admin = 'required|admin_key[admin]';
            } else {
                $admin = 'permit_empty';
            }
            $rules = [
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[50]',
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
                    'rules' => 'required|alpha_space|max_length[255]',
                    'errors' => [
                        'required' => 'Field Nama harus diisi',
                        'alpha_space' => 'Field Nama hanya boleh berisi Huruf dan spasi',
                        'max_length' => 'Maksimum karakter untuk Field Nama adalah 255 karakter'
                    ]
                ],
                'phone_no' => [
                    'label' => 'Phone Number',
                    'rules' => 'required|numeric|min_length[7]|max_length[15]',
                    'errors' => [
                        'required' => 'Field Phone No harus diisi',
                        'numeric' => 'Field Phone No hanya boleh berisi angka',
                        'min_length' => 'Maksimum digit untuk Field Phone No adalah 7 karakter',
                        'max_length' => 'Maksimum digit untuk Field Phone No adalah 15 karakter'
                    ]
                ],
                'email' => [
                    'label' => 'Email',
                    'rules' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
                    'errors' => [
                        'required' => 'Field Email harus diisi',
                        'is_unique' => 'Email sudah dipakai',
                        'valid_email' => 'Email harus valid "(Memakai @ dan .com)"',
                        'min_length' => 'Minimum karakter untuk Field Email adalah 3 karakter',
                        'max_length' => 'Maksimum karakter untuk Field Email adalah 50 karakter'
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]|max_length[255]',
                    'errors' => [
                        'required' => 'Field password harus diisi',
                        'min_length' => 'Minimum karakter untuk Field password adalah 8 karakter',
                        'max_length' => 'Maksimum karakter untuk Field password adalah 255 karakter'
                    ]
                ],
                'password_confirm' => [
                    'label' => 'Confirm Password',
                    'rules' => 'required|matches[password]',
                    'errors' => [
                        'required' => 'Password harus di konfirmasi',
                        'matches' => 'Password konfirmasi harus sama',
                    ]
                ],
                'admin' => [
                    'label' => 'Admin',
                    'rules' => $admin,
                    'errors' => [
                        'required' => 'Field Admin harus diisi',
                        'admin_key' => 'Keycode salah',
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $data = [
                    'validation' => $this->validator,
                    'title' => 'Register',
                    'username' => $this->request->getVar('username'),
                    'name' => $this->request->getVar('name'),
                    'phone_no' => $this->request->getVar('phone_no'),
                    'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'password_confirm' => $this->request->getVar('password_confirm'),
                    'admin' => $this->request->getVar('admin'),
                ];

                return view('pages/register', $data);
            } else {
                $model = new UserModel();

                if ($this->request->getVar('admin') == 'endit') {
                    $newData = 'admin';
                } else {
                    $newData = 'member';
                }

                $newData = [
                    'uuid' => $model->generateUuid(),
                    'username' => $this->request->getVar('username'),
                    'name' => $this->request->getVar('name'),
                    'phone_no' => $this->request->getVar('phone_no'),
                    'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'role' => $newData,
                ];
                $model->save($newData);
                session();
                $session = session();
                $session->setFlashdata('success', 'Successful Registration');
                $data = [
                    'user' => $this->request->getVar('username'),
                    'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                    'remember' => '',

                    'title' => 'Login'
                ];
                return view('pages/login', $data);
            }
        }
        return view('pages/register', $data);
    }

    //! Unfinished function 
    //TODO : Create a function to remember me 
    public function remember()
    {
        $model = new UserModel();
        $user = $model->where('email', $this->request->getVar('email'))->first();
        if ($user) {
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
                'remember' => 'checked',
                'title' => 'Login'
            ];
            return view('pages/login', $data);
        } else {
            $data = [
                'email' => $this->request->getVar('email'),
                'password' => $this->request->getVar('password'),
                'remember' => '',
                'title' => 'Forgot Your Password ? (Unfinished)'
            ];
            return view('pages/forgot-password', $data);
        }
    }
}
