<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;
use App\Models\ModelMahasiswa;

class Mahasiswa extends Seeder
{
    public function run()
    {
        $this->mhs = new ModelMahasiswa();
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 100; $i++) {

            $jurusan = ($faker->randomElements(['sejarah', 'mipa', 'sastra']));
            $nim = $this->mhs->autonumber($jurusan);
            $tglLahir = $faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d');
            $foto = $faker->imageUrl(200, 300, 'animals', true, 'cats');
            $jenkel = ($faker->randomElements(['l', 'p']));
            $agama = ($faker->randomElements(['islam', 'kristen', 'konghucu', 'hindu', 'buddha']));
            $pend = ($faker->randomElements(['sd', 'smp', 'sma', 'smk', 's1']));

            $data = [
                'nim_mhs' => $nim,
                'nama_mhs' => $faker->firstName . ' ' . $faker->lastName,
                'jenis_kelamin' => $jenkel,
                'TmpLahir_mhs' => $faker->city,
                'TglLahir_mhs' => $tglLahir,
                'agama_mhs' => $agama,
                'alamat_mhs' => $faker->address,
                'hp_mhs' => $faker->e164PhoneNumber,
                'jurusan_mhs' => $jurusan,
                'pendidikan' => $pend,
                'foto' => $foto,
            ];

            $this->db->table('mahasiswa')->insert($data);
        }
    }
}
