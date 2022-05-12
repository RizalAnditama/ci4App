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
        'uuid',
        "username",
        "email",
        "phone_no",
        "password",
        "name",
        "role",
        "profile_pic",
        "created_at",
        "updated_at",
        "token",
        "token_expire",
        "status",
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

    /** 
     * Generate UUID from hash
     */
    public function generateUuid()
    {
        $uuid = hash('sha256', random_bytes(8));
        return $uuid;
    }

    /**
     * Before insert callback
     * ------------------------------------------------------------
     * Will be called before inserting a new record, and automatically sets the created_at and updated_at fields.
     */
    protected function beforeInsert(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }

    /** 
     * Before update callback
     * ------------------------------------------------------------
     * Will be called before updating an existing record, and automatically sets the updated_at field.
     */
    protected function beforeUpdate(array $data)
    {
        $data = $this->passwordHash($data);
        return $data;
    }

    /**
     * Password hash callback
     * ------------------------------------------------------------
     * Will be called before inserting or updating a record, and automatically hashes the password field.
     */
    protected function passwordHash(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Get username 
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
     * Get username by email
     * 
     * @return array
     */
    public function getUsername($email = 0)
    {
        $user = $this->where('email', $email)->first();
        return $user;
    }

    /**
     * Get password by user id 
     *
     */
    public function getPassword($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['password'];
    }

    /**
     * Get uuid by email
     */
    public function getUuid($email)
    {
        $user = $this->where('email', $email)->first();
        return $user['uuid'];
    }

    /**
     * Get all UUIDs
     */
    public function getAllUuid()
    {
        $query = $this->db->query("SELECT uuid FROM users");
        $result = $query->getResult();
        return $result;
    }

    /**
     * Check if uuid exists
     */
    public function uuidExists($uuid)
    {
        $user = $this->where('uuid', $uuid)->first();
        return $user;
    }

    /**
     * Create token for user
     */
    public function createToken($email)
    {
        $user = $this->where('email', $email)->first();
        $data = [
            'token' => substr(hash('md5', microtime(), false), 0, 16),
            'token_expire' => date('Y-m-d H:i:s', strtotime('+15 minutes')),
        ];
        $this->update($user['id'], $data);
        return $data['token'];
    }

    /**
     * --------------------------------------------------------------------------------
     * Check Old Password
     * --------------------------------------------------------------------------------
     * Get old password with query, then compare it with new password 
     * @var string
     */
    public function checkOldPassword(string $oldPassword)
    {
        $model = new UserModel();

        $password = $model->where('id', session()->get('id_user'))->first()['password'];
        $oldPassword = password_verify($oldPassword, $password);

        return $oldPassword;
    }

    /**
     * Get Profile Pic by user id
     */
    public function getProfilePic($id_user)
    {
        $user = $this->where('id', $id_user)->first();
        return $user['profile_pic'];
    }
}
