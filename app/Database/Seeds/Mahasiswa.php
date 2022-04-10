<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class Mahasiswa extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'nim_mhs' => $faker->numerify('######'),
                'nama_mhs' => $faker->firstName . ' ' . $faker->lastName,
                'TmpLahir_mhs'  => $faker->city,
                'TglLahir_mhs' => $faker->date('Y-m-d', 'now'),
                'alamat_mhs'  => $faker->address,
                'hp_mhs'
                => $faker->numerify('###########'),
                'jurusan_mhs'  => $faker->randomElements(['Sejarah', 'MIPA', 'Sastra']),
                // 'created_at' => Time::createFromTimestamp($faker->unixTime()),
                // 'updated_at' => Time::now(),
            ];
            $this->db->table('mahasiswa')->insert($data);
        }
    }
}
