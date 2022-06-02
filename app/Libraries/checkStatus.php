<?php

namespace App\Libraries;

use App\Controllers\UserController;
use App\Models\UserModel;

class checkStatus
{
    /**
     * ! This thing is not working properly yet !
     * -----------------------------------------------------------------------------------
     * Check if the user is logged in, then automatically updates the status of the user
     */
    public function checkStatus()
    {
        $userModel = new UserModel();
        if (null !== (session()->get('isLoggedIn'))) {
            $user = $userModel->getUser(session()->get('username'));
            if (session()->get('isLoggedIn') == true) {
                $data = [
                    'status' => 'active',
                ];
            } else {
                $data = [
                    'status' => 'inactive',
                ];
            }
            $userModel->update($user['id'], $data);
        }
    }

    public function checkCookies()
    {
        $model = new UserModel();
        $controller = new UserController();
        if (isset($_COOKIE['uuid']) && isset($_COOKIE['token'])) {
            $uuid = $_COOKIE['uuid'];
            $token = $_COOKIE['token'];

            // ambil username berdasarkan uuid
            $user = $model->where('uuid', $uuid)->first();
            if ($user === null) {
                $controller->logout();
            }

            $username = $user['username'];
            if (!password_verify($username, $token)) {
                $controller->logout();
            }

            session()->set('isLoggedIn', true);
        }
    }
}
