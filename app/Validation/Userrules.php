<?php

namespace App\Validation;

use App\Models\UserModel;

class Userrules
{
    public function validateUser(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        // get user data by email or username
        $user = $model->where('email', $data['user'])->orwhere('username', $data['user'])->first();

        if (!$user) {
            return false;
        }

        return password_verify($data['password'], $user['password']);
    }

    public function admin_key(string $str, string $fields, array $data)
    {
        if ($data['admin'] == 'endit') {
            return true;
        }

        return false;
    }

    // check if password input is the same as the old password
    public function checkOldPassword(string $str, string $fields, array $data)
    {
        $model = new UserModel();

        $password = $model->where('id', session()->get('id_user'))->first()['password'];
        $oldPassword = password_verify($data['oldPassword'], $password);

        if ($oldPassword) {
            return true;
        }

        return false;
    }

    // check if email or username is exist in database
    // if exist, return true
    public function is_exist(string $str, string $fields, array $data)
    {
        $model = new UserModel();
        // get user data by email or username
        $user = $model->where('email', $data['user'])->orwhere('username', $data['user'])->first();

        if ($user) {
            return true;
        }

        return false;
    }
}
