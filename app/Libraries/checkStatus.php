<?php

namespace App\Libraries;

use App\Models\UserModel;

class checkStatus
{
    public function __construct()
    {
        $userModel = new UserModel();
    }

    /**
     * ! This thing is not working yet !
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
}
