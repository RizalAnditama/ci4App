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
}
