<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>

<?php

use App\Models\ModelMahasiswa;
use App\Controllers\Mahasiswa;
use CodeIgniter\Filters\CSRF;

$session = \Config\Services::session();
//? Tempat testing dan debug
// $milliseconds = round(microtime(true) * 1000);

// $bruh = [
//     'ur mom' => 'fat',
//     'lol' => 'bruh'
// ];

// //* testing time needed for fetching the array
// $before = microtime(true);

// for ($i = 0; $i < 100000; $i++) {
//     serialize($mahasiswa);
// }
// $after = microtime(true);
// d($mahasiswa);
// dd(($after - $before) / $i . " sec/serialize\n");
// 
?>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($session->getFlashdata('fail_add')) : ?>
            $('#addNewDataModal').modal('show');
        <?php endif; ?>
        <?php if ($session->getFlashdata('fail_edit')) : ?>
            $('#editDataModal' + <?php echo $session->get('id'); ?>).modal('show');
        <?php endif; ?>
    });
</script>

<div class="container my-4">
    <div class="d-flex justify-content-between my-3">
        <form action="" method="get">
            <div class="input-group mb-3">
                <input id="search" name="keyword" type="search" class="form-control" placeholder="Masukan kata kunci..." value="<?php echo session()->get('keyword') ?>" list="datalistOptions" aria-describedby="search-button" autofocus>
                <datalist id="datalistOptions">
                    <?php foreach ($nama as $row) : ?>
                        <option value="<?= $row ?>">
                        <?php endforeach ?>
                        <?php foreach ($nim as $row) : ?>
                        <option value="<?= $row ?>">
                        <?php endforeach ?>
                        <?php foreach ($TmpLahir as $row) : ?>
                        <option value="<?= $row ?>">
                        <?php endforeach ?>
                        <?php foreach ($hp as $row) : ?>
                        <option value="<?= $row ?>">
                        <?php endforeach ?>
                        <option value="MIPA">
                        <option value="Sejarah">
                        <option value="Sastra">
                </datalist>
                <button id="search-button" class="btn btn-outline-primary" type="submit" name="submit">Cari</button>
                <?php if (session()->getFlashdata('home')) : ?>
                    <a class="btn btn-success" href="<?php echo base_url("mahasiswa") ?>">Home</a>
                <?php endif; ?>
            </div>
        </form>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewDataModal">Tambah Data</button>
    </div>

    <div class="success">
        <?php if ($session->getFlashdata('success_add')) : ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div class="text ms-3">
                    <strong><?= $session->getFlashdata('success_add'); ?></strong>
                    <a class="mb-0 alert-link" data-bs-toggle="modal" data-bs-target="<?php echo ($session->getFlashdata('success_add')) ? '#editDataModal' . session()->getFlashdata('id') : '';  ?>" style="cursor: pointer;"><?= session()->get('nama') . ' ' . '(' . $session->getFlashdata('nim') . ')'; ?></a>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if ($session->getFlashdata('success_edit')) : ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                    <use xlink:href="#check-circle-fill" />
                </svg>
                <div class="text ms-3">
                    <strong><?= $session->getFlashdata('success_edit'); ?></strong>
                    <br>
                    <a class="mb-0 alert-link" data-bs-toggle="modal" data-bs-target="<?php echo ($session->getFlashdata('success_edit') ? '#editDataModal' . $session->get('id') : '');  ?>" style="cursor: pointer; text-decoration: underline;"><?= session()->get('nama') . ' ' . '(' . session()->get('nim') . ')'; ?></a>
                </div>
                <button onclick="resetColor()" type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>

    <div class="fail">
        <?php if ($validation->getErrors()) : ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div class="ms-3">
                    <h4 class="alert-heading">Input tidak sesuai ketentuan</h4>
                    <span>Gagal <?php echo $session->getFlashdata('fail_add') ? 'menambahkan data' : ($session->getFlashdata('fail_edit') ? 'mengedit' . ' data ' . '<a class="alert-link" data-bs-toggle="modal" data-bs-target="#editDataModal' . $session->get('id') . '" style="cursor: pointer;text-decoration: none;">' .  session()->get('nama') . ' ' . '(' . old('nim_edit') . ')' . '</a>' : 'menambahkan/mengedit data'); ?></span>
                    <br>
                    <a class="mb-0 alert-link" data-bs-toggle="modal" data-bs-target="<?php echo $session->getFlashdata('fail_add') ? '#addNewDataModal' : ($session->getFlashdata('fail_edit') ? '#editDataModal' . $session->get('id') : ' ');  ?>" style="cursor: pointer; text-decoration: underline;">Coba Lagi</a>
                </div>
            </div>
        <?php endif;
        ?>
    </div>

    <div class="info">
        <?php if ($session->getFlashdata('deleted')) : ?>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                    <use xlink:href="#info-fill" />
                </svg>
                <div class="text ms-3">
                    <strong><?= $session->getFlashdata('deleted'); ?></strong>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
    <?php
    // cek kalo databases ada isinya
    // dd(session()->getFlashdata());
    if (session()->getFlashdata('fail_search') === true) { ?>
        echo session()->getFlashdata('fail_search');
    <?php } else if (empty($mahasiswa)) { ?>
        <div class="container">
            <div class="row">
                <h3 class="text-center">Data <?php echo $keyword = (session()->get('keyword')) ? '"' . session()->get('keyword') . '"' : 'Mahasiswa'; ?> Kosong</h3>
                <a class="text-center" data-bs-toggle="modal" data-bs-target="#addNewDataModal" style="cursor: pointer;text-decoration: none; color:aqubluea"><i class="bi bi-cloud-arrow-up-fill"></i> Silahkan tambah data <i class="bi bi-cloud-arrow-up-fill"></i></a>
            </div>
        </div>
    <?php } else { ?>
        <div class="mb-3" style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIM</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Tempat<br>Tanggal Lahir</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Nomor HP</th>
                        <th scope="col">Jurusan</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no = 1 + (5 * ($page - 1));
                    // $no = count($mahasiswa) * $page - (count($mahasiswa) - 1);

                    foreach ($mahasiswa as  $row) :
                    ?>

                        <tr>
                            <th><?= $no++; ?></th>
                            <td><?= $row->nim_mhs ?></td>
                            <td><?= $row->nama_mhs ?></td>
                            <td><?= $row->TmpLahir_mhs . '<br>' . $row->TglLahir_mhs ?></td>
                            <td><?= $row->alamat_mhs ?></td>
                            <td><?= $row->hp_mhs ?></td>
                            <td><?= $row->jurusan_mhs ?></td>
                            <td>
                                <div class="dropstart">
                                    <a href="#" class="" data-bs-toggle="dropdown" aria-expanded="false" data-toggle="tooltip" data-placement="right" title="Action">
                                        <i class="bi bi-three-dots-vertical col-6" style="color: <?php echo (session()->getFlashdata('fail_edit')) ? (($session->get('id') === $row->id_mhs) ? 'orange' : 'black') : ((session()->getFlashdata('success_edit')) ? (($session->get('id') === $row->id_mhs) ? 'green' : 'black') : 'black'); ?>;"></i>
                                    </a>
                                    <ul class="dropdown-menu p-0">
                                        <!-- Tombol Edit, Hapus, dan Info -->
                                        <li>
                                            <button class="w-75 btn btn-outline-success" id="editModalBtn" data-bs-toggle="modal" data-bs-target="#editDataModal<?php echo $row->id_mhs; ?>">
                                                <div class="row">
                                                    <i id="icon-pencil" class="col-4 bi bi-pencil-square" style="cursor: pointer;text-decoration: none; color:green;"></i>
                                                    <span class="col-4 mx-auto">Edit</span>
                                                    <i class="col-4"></i>
                                                </div>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="w-75 btn btn-outline-info" id="btnInfo" data-bs-toggle="modal" data-bs-target="#modalInfo<?php echo $row->id_mhs; ?>" style="color: blue; cursor: pointer;text-decoration: none;">
                                                <div class="row">
                                                    <i class="col-4 bi bi-info-circle-fill"></i>
                                                    <span class="col-4 mx-auto">Info</span>
                                                    <i class="col-4"></i>
                                                </div>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="w-75 btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $row->id_mhs; ?>">
                                                <div class="row">
                                                    <i class="col-4 bi bi-trash-fill" style="color: red; cursor: pointer;text-decoration: none;"></i>
                                                    <?php $session->markAsTempdata('id', 2);
                                                    //Hapus session id biar tombolnya bisa balik jadi warning lagi 
                                                    ?>
                                                    <span class="col-4 mx-auto">Hapus</span>
                                                    <i class="col-4"></i>
                                                </div>
                                            </button>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                <?php endforeach;
                } ?>
                </tbody>
            </table>
        </div>
        <?= $pager->links('mahasiswa', 'mahasiswa_pagination') ?>
</div>


<!-- Modal Tambah Data -->
<div id="addNewDataModal" class=" modal fade addNewDataModal" tabindex="-1" aria-labelledby="AddNewDataModalLabel" aria-labelledby="formAddNewData" aria-hidden="true">
    <!-- Tambah Data Modal Dialog -->
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Tambah data baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                echo form_open_multipart('mahasiswa/SimpanData');
                echo csrf_field(); ?>
                <div class="form-floating mb-3">
                    <input type="text" name="nama" size="255" placeholder="Nama" id="inputNama" class="form-control <?= ($validation->hasError('nama')) ? 'is-invalid' : ''; ?>" value="<?= old('nama'); ?>" required>
                    <label for="inputNama">Nama</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('nama'); ?>
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="TmpLahir" size="255" placeholder="TempatLahir" id="inputTempatLahir" class="form-control <?= ($validation->hasError('TmpLahir')) ? 'is-invalid' : ''; ?>" value="<?= old('TmpLahir'); ?>" required>
                    <label for="inputTempatLahir">Tempat Lahir</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('TmpLahir'); ?>
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <input type="date" name="TglLahir" placeholder="TanggalLahir" id="inputTanggalLahir" class="form-control <?= ($validation->hasError('TglLahir')) ? 'is-invalid' : ''; ?>" value="<?= old('TglLahir'); ?>" required>
                    <label for="inputTanggalLahir">Tanggal Lahir</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('TglLahir'); ?>
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="alamat" size="255" placeholder="Alamat" id="inputAlamat" class="form-control <?= ($validation->hasError('alamat')) ? 'is-invalid' : ''; ?>" value="<?= old('alamat'); ?>" required>
                    <label for="inputAlamat">Alamat</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('alamat'); ?>
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <input type="tel" name="telepon" maxlength="13" placeholder="Telepon" id="inputTelepon" pattern="{0-9}+" class="form-control <?= ($validation->hasError('telepon')) ? 'is-invalid' : ''; ?>" value="<?= old('telepon'); ?>" required>
                    <label for="inputTelepon">HP/Telepon</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('telepon'); ?>
                    </div>

                </div>
                <div class="form-floating mb-3">
                    <select class="form-control <?= ($validation->hasError('jurusan')) ? 'is-invalid' : ''; ?>" name="jurusan" id="jurusan" required>
                        <option selected disabled value="">Pilih...</option>
                        <option value="sejarah" <?= (old('jurusan') === 'sejarah') ? 'selected' : ''; ?>>Sejarah</option>
                        <option value="mipa" <?= (old('jurusan') === 'mipa') ? 'selected' : '' ?>>Matematika & IPA</option>
                        <option value="sastra" <?= (old('jurusan') === 'sastra') ? 'selected' : ''; ?>>Sastra</option>
                    </select>
                    <label for="inputJurusan">Jurusan</label>

                    <div class='invalid-feedback'>
                        <?= $error = $validation->getError('jurusan'); ?>
                    </div>

                </div>
                <div class="form-floating row mb-3">
                    <div class="custom-file">
                        <label for="foto" class="col-form-label custom-file-label">Pilih foto mahasiswa...</label>
                        <input id="foto" name="foto" type="file" class="custom-file-input  form-control is-<?= ($validation->getError('foto')) ? 'invalid' : '' ?>" value="<?php old('foto'); ?>">
                        <img id="blah" src="#" alt="Foto Mahasiswa" style="max-width: 100px;">
                        <div class='invalid-feedback'>
                            <?= $error = $validation->getError('foto'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="SimpanData" value="simpandata" id="SimpanData" class="btn btn-primary">Tambah</button>
                <button type="button" name="tutup" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <div class=" response-message ms-3">
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Edit data -->
<?php
$id_mhs = 0;

foreach ($mahasiswa as  $row) :
    $id_mhs++;
?>

    <div class="modal fade" id="editDataModal<?php echo $row->id_mhs; ?>" tabindex="-1" aria-labelledby="EditDataModalLabel" tabindex="-1" aria-labelledby="formEditData" aria-hidden="true">
        <!-- Edit Data Modal Dialog -->
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Edit Data <?php echo $row->nama_mhs . ' ' .  '(' . $row->nim_mhs . ')' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= form_open_multipart('mahasiswa/UpdateData/');
                    echo csrf_field();
                    ?>
                    <div class="row">
                        <input type="number" hidden name="id" placeholder="id" id="inputId" class="form-control" value="<?php echo $row->id_mhs ?>">
                        <div class="image col-6 d-flex justify-content-center">
                            <img class="d-block m-auto" src="<?php echo $row->foto; ?>" style="width: 200px;" alt="Foto <?php echo $row->nama_mhs . ' ' . '(' . $row->nim_mhs . ')' ?>">
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-0" hidden>
                                <input type="hidden" name="nim_edit" maxlength="7" placeholder="nim" id="inputNim" class="form-control" value="<?php echo $row->nim_mhs; ?>" hidden>
                                <label for="inputNim">NIM</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="nama_edit" size="255" placeholder="Nama" id="inputNama" class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('nama_edit')) ? 'is-invalid' : 'is-valid'; ?><?php endif; ?>" value="<?php echo old('id') === $row->id_mhs ? old('nama_edit') : $row->nama_mhs ?>" required>
                                <label for="inputNama">Nama</label>

                                <?php if (old('id') === $row->id_mhs) { ?>
                                    <div class='invalid-feedback'>
                                        <?= $error = $validation->getError('nama_edit'); ?>
                                    </div><?php
                                        } ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="TmpLahir_edit" size="255" placeholder="TempatLahir" id="inputTempatLahir" class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('TmpLahir_edit')) ? 'is-invalid' : 'is-valid'; ?><?php endif; ?>" value="<?php echo old('id') === $row->id_mhs ? old('TmpLahir_edit') : $row->TmpLahir_mhs ?>" required>
                                <label for="inputTempatLahir">Tempat Lahir</label>

                                <?php if (old('id') === $row->id_mhs) {
                                ?>
                                    <div class='invalid-feedback'>
                                        <?= $error = $validation->getError('TmpLahir_edit'); ?>
                                    </div><?php

                                        } ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="date" name="TglLahir_edit" placeholder="TanggalLahir" id="inputTanggalLahir" class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('TglLahir_edit')) ? 'is-invalid' : 'is-valid'; ?><?php endif; ?>" value="<?php echo old('id') === $row->id_mhs ? old('TglLahir_edit') : $row->TglLahir_mhs ?>" required>
                                <label for="inputTanggalLahir">Tanggal Lahir</label>

                                <?php if (old('id') === $row->id_mhs) {
                                ?>
                                    <div class='invalid-feedback'>
                                        <?= $error = $validation->getError('TglLahir_edit'); ?>
                                    </div><?php

                                        } ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="alamat_edit" size="255" placeholder="Alamat" id="inputAlamat" class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('alamat_edit')) ? 'is-invalid' : 'is-valid'; ?><?php endif; ?>" value="<?php echo old('id') === $row->id_mhs ? old('alamat_edit') : $row->alamat_mhs ?>" required>
                                <label for="inputAlamat">Alamat</label>

                                <?php if (old('id') === $row->id_mhs) {
                                ?>
                                    <div class='invalid-feedback'>
                                        <?= $error = $validation->getError('alamat_edit'); ?>
                                    </div><?php

                                        } ?>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="tel" name="telepon_edit" maxlength="13" placeholder="Telepon" id="inputTelepon" pattern="{0-9}+" class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('telepon_edit')) ? 'is-invalid' : 'is-valid'; ?><?php endif; ?>" value="<?php echo old('id') === $row->id_mhs ? old('telepon_edit') : $row->hp_mhs ?>" required>
                                <label for="inputTelepon">HP/Telepon</label>

                                <?php if (old('id') === $row->id_mhs) {
                                    if ($validation->getError('telepon_edit')) { ?>
                                        <div class='invalid-feedback'>
                                            <?= $error = $validation->getError('telepon_edit'); ?>
                                        </div><?php
                                            }
                                        } ?>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control <?php if (old('id') === $row->id_mhs) : ?><?= ($validation->hasError('jurusan_edit')) ? 'is-invalid' : ''; ?><?php endif; ?>" name="jurusan_edit" id="jurusan_edit" required>
                                    <?php if ($validation->hasError('jurusan_edit')) { ?>
                                        <option value="sejarah" <?= (old('jurusan') === 'sejarah') ? 'selected' : ''; ?>>Sejarah</option>
                                        <option value="mipa" <?= (old('jurusan') === 'mipa') ? 'selected' : '' ?>>Matematika & IPA</option>
                                        <option value="sastra" <?= (old('jurusan') === 'sastra') ? 'selected' : ''; ?>>Sastra</option>
                                    <?php } else { ?>
                                        <option value="sejarah" <?= ($row->jurusan_mhs === 'Sejarah') ? 'selected' : ''; ?>>Sejarah</option>
                                        <option value="mipa" <?= ($row->jurusan_mhs === 'MIPA') ? 'selected' : '' ?>>Matematika & IPA</option>
                                        <option value="sastra" <?= ($row->jurusan_mhs === 'Sastra') ? 'selected' : ''; ?>>Sastra</option>
                                    <?php } ?>
                                </select>
                                <label for="inputJurusan">Jurusan</label>
                                <?php if (old('id') === $row->id_mhs) {
                                ?>
                                    <div class='invalid-feedback'>
                                        <?= $error = $validation->getError('jurusan_edit'); ?>
                                    </div><?php

                                        } ?>
                            </div>
                            <div class=" form-floating mb-0">
                                <input type="file" name="foto_edit" id="foto_edit" class="form-control">
                                <label for="foto_edit">Edit Foto</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" value="UpdateData" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>


    <!-- Hapus Data -->
    <div class="modal fade deleteModal" id="deleteModal<?= $row->id_mhs; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-labelledby="formDeleteData" aria-hidden="true">
        <!-- Data Hapus Dialog -->
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Data <?php echo $row->nama_mhs . ' ' . '(' . $row->nim_mhs . ')' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo form_open('mahasiswa/hapus');
                    echo csrf_field(); ?>
                    <div class="form-floating mb-0">
                        <input type="number" name="id" placeholder="id" class="form-control" value="<?= $row->id_mhs; ?>" hidden>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="nim" placeholder="nim" id="inputNim" class="form-control" value="<?= $row->nim_mhs; ?>" disabled>
                        <label for="inputNim">NIM</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="nama" placeholder="Nama" id="inputNama" class="form-control" value="<?= $row->nama_mhs; ?>" disabled>
                        <label for="inputNama">Nama</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="TmpLahir" size="255" placeholder="TempatLahir" id="inputTempatLahir" class="form-control" value="<?= $row->TmpLahir_mhs; ?>" disabled>
                        <label for="inputTempatLahir">Tempat Lahir</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="date" name="TglLahir" placeholder="TanggalLahir" id="inputTanggalLahir" class="form-control" value="<?= $row->TglLahir_mhs; ?>" disabled>
                        <label for="inputTanggalLahir">Tanggal Lahir</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea type="text" name="alamat" placeholder="Alamat" id="inputAlamat" class="form-control" disabled><?= $row->alamat_mhs; ?></textarea>
                        <label for="inputAlamat">Alamat</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="tel" name="telepon" placeholder="Telepon" id="inputTelepon" class="form-control" value="<?= $row->hp_mhs; ?>" disabled>
                        <label for="inputTelepon">No. HP</label>
                    </div>
                    <div class="form-floating mb-0">
                        <input type="text" name="jurusan" placeholder="Jurusan" id="inputJurusan" class="form-control" value="<?= $jurusan = ($row->jurusan_mhs === 'Sejarah') ? 'Sejarah' : (($row->jurusan_mhs === 'MIPA') ? 'Matematika & IPA' : (($row->jurusan_mhs === 'Sastra') ? 'Sastra' : '')); ?>" disabled>
                        <label for="inputJurusan">Jurusan</label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-evenly">
                    <h5 class="text-center">Yakin hapus data <?php echo $row->nama_mhs . ' ' . '(' . $row->nim_mhs . ')' ?> ?</h5>
                    <div class="d-inline">
                        <form action="" method="post" class="d-inline text-center">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <a href="<?php echo base_url('mahasiswa/hapus/' . $row->id_mhs) ?>" type="submit" class="btn btn-danger">Hapus</a>
                        </form>
                        <button class="btn btn-secondary" type="button" name="tutup" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>

    <!-- Modal info -->
    <div class="modal fade" id="modalInfo<?php echo $row->id_mhs ?>" tabindex="-1" aria-labelledby="modalInfo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalInfo">Info <?php echo $row->nama_mhs . ' ' .  '(' . $row->nim_mhs . ')' ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="primeInfo">
                        <div class="image col-6 d-flex justify-content-center">
                            <?php
                            $faker = \Faker\Factory::create('id_ID'); ?>
                            <!-- <img class=" d-block m-auto" src="https://i.picsum.photos/id/815/200/300.jpg?hmac=Vd0SL31jtPA-FMvY___e5hp84IGLTUjtVJY4qUL6hOs" style="width: 200px;"> -->
                            <img class="d-block m-auto" src="<?php echo $row->foto; ?>" style="width: 200px;" alt="Foto <?php echo $row->nama_mhs . ' ' . '(' . $row->nim_mhs . ')' ?>">
                        </div>
                        <div class="primeContent col-6">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>NIM</th>
                                        <th>:</th>
                                        <th><?= $row->nim_mhs ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td><?= $row->nama_mhs ?></td>
                                    </tr>
                                    <tr>
                                        <td>Tempat/Tanggal Lahir</td>
                                        <td>:</td>
                                        <td><?= $row->TmpLahir_mhs . '<br>' . $row->TglLahir_mhs ?></td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td><?= $row->alamat_mhs ?></td>
                                    </tr>
                                    <tr>
                                        <td>No. HP</td>
                                        <td>:</td>
                                        <td><?= $row->hp_mhs ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jurusan</td>
                                        <td>:</td>
                                        <td><?= $row->jurusan_mhs ?></td>
                                    </tr>
                                    <tr>
                                        <td>Inputed at</td>
                                        <td>:</td>
                                        <td><?= $row->created_at ?></td>
                                    </tr>
                                    <tr>
                                        <td>Updated at</td>
                                        <td>:</td>
                                        <td><?= $row->updated_at ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script type="text/javascript">
    // $(document).ready(function() {

    //     // Buat buka modal lewar url (Gangerti cara makenya)
    //     if (window.location.href.indexOf('#editDataModal' + $id) != -1) {
    //         $('#editDataModal' + $id).modal('show');
    //     }

    // });

    // Reset warna tombol saat klik close alerts
    function resetColor() {
        var element = document.getElementById("icon-pencil").style.color = "black";
    }

    // Focus Input saat modal kebuka
    var myModal = document.getElementById('addNewDataModal')
    var myInput = document.getElementById('inputNama')

    myModal.addEventListener('shown.bs.modal', function() {
        myInput.focus();
    })

    // Nampilin gambar saat input gambar
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#foto").change(function() {
        readURL(this);
    });


    //  change color icon when clicked
    // then revert it when clicked outside
    $('.bi-three-dots-vertical').click(function() {
        $(this).css('color', 'orange');
    });
    $(document).mouseup(function(e) {
        var container = $(".dropstart");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('.bi-three-dots-vertical').css('color', 'black');
        }
    });

    // // Focus Input saat ngetik
    // function inputFocus() {
    //     document.getElementById("search").focus();
    // }
</script>


<?php
// d(round(microtime(true) * 1000) - $milliseconds);
?>

<?= $this->endSection() ?>