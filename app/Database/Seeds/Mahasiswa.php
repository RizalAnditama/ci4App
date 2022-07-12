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

            $nama = $faker->firstName . ' ' . $faker->lastName;
            $jurusan = ($faker->randomElements(['sejarah', 'mipa', 'sastra']));
            $nim = $this->mhs->autonumber($jurusan);

            $jenkel = ($faker->randomElements(['l', 'p']));
            $tglLahir = $faker->dateTimeBetween('-30 years', '-20 years')->format('Y-m-d');
            $agama = ($faker->randomElements(['islam', 'kristen', 'konghucu', 'hindu', 'buddha']));

            $hp = str_replace('+', '0', $faker->e164PhoneNumber);
            $pend = ($faker->randomElements(['SD', 'SMP', 'SMA', 'SMK', 'sS1']));
            $foto = $faker->imageUrl(200, 300, 'animals', true, 'cats');

            $data = [
                'nim_mhs' => $nim,
                'nama_mhs' => $nama,
                'jenis_kelamin' => $jenkel,
                'TmpLahir_mhs' => $faker->city,
                'TglLahir_mhs' => $tglLahir,
                'agama_mhs' => $agama,
                'alamat_mhs' => $faker->address,
                'hp_mhs' => $hp,
                'jurusan_mhs' => $jurusan,
                'pendidikan' => $pend,
                'foto' => $foto,
            ];

            $this->db->table('mahasiswa')->insert($data);
        }
    }
}
