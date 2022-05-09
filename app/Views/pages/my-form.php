<?= $this->extend('layouts/app'); ?>

<?= $this->section('body'); ?>

<?php
if (session()->getFlashdata("success") != null) {
?>
    <h3><?= session()->getFlashdata("success")?></h3>
<?php
}
if (session()->getFlashdata("error") != null) {
?>
    <h3><?= session()->getFlashdata("error")?></h3>
<?php
}
?>

<form action="<?= site_url('Test') ?>" method="post" enctype="multipart/form-data">
    <p>
        Name: <input type="text" name="name" placeholder="Enter name" />
    </p>

    <p>
        Email: <input type="email" name="email" placeholder="Enter email" />
    </p>

    <p>
        Phone No: <input type="text" name="phone_no" placeholder="Enter phone" />
    </p>

    <p>
        File Upload: <input type="file" name="profile_image" />
    </p>
    <p>
        <button type="submit">Submit</button>
    </p>
</form>

<?= $this->endSection(); ?>