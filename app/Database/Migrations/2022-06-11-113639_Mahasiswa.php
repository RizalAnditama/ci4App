<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mahasiswa extends Migration
{
    public function up()
    {
        // Membuat kolom/field untuk tabel mahasiswa
        $this->forge->addField([
            'id_mhs'          => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nim_mhs'          => [
                'type'           => 'INT',
                'constraint'     => 11,
            ],
            'nama_mhs'          => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'jenis_kelamin'     => [
                'type'          => 'ENUM',
                'constraint'    => ['l', 'p'],
            ],
            'TmpLahir_mhs'      => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'TglLahir_mhs'      => [
                'type'          => 'DATE',
            ],
            'agama_mhs'         => [
                'type'          => 'ENUM',
                'constraint'    => ['islam', 'kristen', 'konghucu', 'hindu', 'buddha']
            ],
            'alamat_mhs'        => [
                'type'          => 'TEXT',
            ],
            'hp_mhs'            => [
                'type'          => 'CHAR',
                'constraint'    => 15,
            ],
            'jurusan_mhs'       => [
                'type'          => 'ENUM',
                'constraint'    => ['Sejarah', 'MIPA', 'Sastra'],
                'NULL'          => true,
                'DEFAULT'       => null,
            ],
            'pendidikan'       => [
                'type'          => 'ENUM',
                'constraint'    => ['sd', 'smp', 'sma', 'smk', 's1'],
                'NULL'          => true,
                'DEFAULT'       => null,
            ],
            'foto'              => [
                'type'          => 'VARCHAR',
                'constraint'    => 255,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'updated_at'        => [
                'type'          => 'TIMESTAMP',
                'default'       => 'CURRENT_TIMESTAMP',
                'null'          => true,
            ],
        ]);

        // Membuat primary key
        $this->forge->addKey('id_mhs', true);

        // Membuat tabel mahasiswa
        $this->forge->createTable('mahasiswa', TRUE);
    }

    public function down()
    {
        // menghapus tabel mahasiswa
        $this->forge->dropTable('mahasiswa');
    }
}
