<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'uuid' => [
                'type'           => 'CHAR',
                'constraint'     => '36',
                'null'           => true,
            ],
            'username'          => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'email'             => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
            ],
            'phone_no'           => [
                'type'           => 'CHAR',
                'constraint'     => '15',
            ],
            'password'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'name'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '100',
            ],
            'role'       => [
                'type'           => 'ENUM',
                'constraint'     => ['admin', 'member'],
                'default'        => 'member',
            ],
            'profile_pic'       => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'default'        => 'images/profile/default-profile.jpg',
            ],

            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NULL',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'token' => [
                'type'           => 'CHAR',
                'constraint'     => '16',
                'null'            => true,
            ],
            'token_expire' => [
                'type'           => 'TIMESTAMP',
                'null'            => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'inactive'],
                'default'        => 'inactive',
            ],

        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
