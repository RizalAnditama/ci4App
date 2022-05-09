<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'users';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [
        "username",
        "email",
        "phone_no",
        "password",
        "name",
        "role",
        "profile_pic",
        "created_at",
        "updated_at",
    ];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = ["beforeInsert"];
    protected $afterInsert          = [];
    protected $beforeUpdate         = ["beforeUpdate"];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    protected function beforeInsert(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }

    protected function passwordHash(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Get username 
     *
     * @var string
     */
    public function getUser($username = false)
    {
        if ($username == false) {
            $user = $this->findAll();
        } else {
            $user = $this->where('username', $username)->first();
        }

        return $user;
    }

    /**
     * Get password by user id 
     *
     * @var string
     */
    public function getPassword($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['password'];
    }

    /**
     * --------------------------------------------------------------------------------
     * Check Old Password
     * ********************************************************************************************
     * Get old password with query, then compare it with new password 
     * @var string
     */
    public function checkOldPassword($oldPassword)
    {
        $model = new UserModel();

        $password = $model->where('id', session()->get('id_user'))->first()['password'];
        $oldPassword = password_verify($oldPassword, $password);

        return $oldPassword;
    }

    /**
     * Get Profile Pic by user id
     * @var string
     */
    public function getProfilePic($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['profile_pic'];
    }
    // // Update the user's profile data
    // public function updateProfile($data)
    // {
    //     $user = $this->find($this->session->get('id'));

    //     if ($user['username'] != $data['username']) {
    //         $user = $this->where('username', $data['username'])->first();
    //         if ($user) {
    //             $data['errors'] = 'Username already exists';
    //             return json_encode($data);
    //         }
    //     }

    //     if ($user['email'] != $data['email']) {
    //         $user = $this->where('email', $data['email'])->first();
    //         if ($user) {
    //             $data['errors'] = 'Email already exists';
    //             return json_encode($data);
    //         }
    //     }

    //     if ($user['phone_no'] != $data['phone_no']) {
    //         $user = $this->where('phone_no', $data['phone_no'])->first();
    //         if ($user) {
    //             $data['errors'] = 'Phone number already exists';
    //             return json_encode($data);
    //         }
    //     }

    //     $user = $this->find($this->session->get('id'));
    //     $user->username = $data['username'];
    //     $user->email = $data['email'];
    //     $user->phone_no = $data['phone_no'];
    //     $user->name = $data['name'];
    //     $user->role = $data['role'];
    //     $user->profile_pic = $data['profile_pic'];
    //     $user->save();
    // }
}
