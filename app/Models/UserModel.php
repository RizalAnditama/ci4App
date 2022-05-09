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

    public function getUser($username = false)
    {
        if ($username == false) {
            $user = $this->findAll();
        } else {
            $user = $this->where('username', $username)->first();
        }

        return $user;
    }

    // check if email already in database
    public function checkEmail($email)
    {
        $user = $this->where('email', $email)->first();

        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    // Get password by id_user
    public function getPassword($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['password'];
    }

    // Get old password with query
    // then compare with new password
    public function checkOldPassword($oldPassword)
    {
        $model = new UserModel();

        $password = $model->where('id', session()->get('id_user'))->first()['password'];
        $oldPassword = password_verify($oldPassword, $password);

        return $oldPassword;
    }

    // Get profile picture by id_user
    public function getProfilePic($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['profile_pic'];
    }

    public function timestampFile($file)
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $name = pathinfo($file, PATHINFO_FILENAME);
        $newName = time() . '_' . $name;
        $newFile = $newName . '.' . $ext;
        return $newFile;
    }
}
