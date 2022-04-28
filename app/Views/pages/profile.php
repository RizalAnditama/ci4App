<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>
<script>
    <?php if (null != $errors) : ?>
        $(document).ready(function() {
            $('#editProfile').modal('show');
        });
    <?php endif ?>
</script>

<?php $milliseconds = round(microtime(true) * 1000) ?>

<?php
if (null != session()->getFlashdata('errors')) {
    $errors = session()->getFlashdata('errors');
}
?>

<div id="profile p-0 m-0">
    <div class="container py-5">

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="<?php echo $profile_pic; ?>" alt="avatar" class="rounded-circle img-fluid" style="min-width: 100px; min-height: 100px;">
                        <h5 class="my-3"><?= session()->get('name') ?></h5>
                        <p class="text-muted mb-1"><?php
                                                    $role = session()->get('role');
                                                    $role = ucfirst($role);
                                                    echo $role;
                                                    ?></p>
                        <p class="text-muted mb-4"><?php echo session()->get('email') ?></p>
                        <button type="button" class="btn btn-outline-primary ms-1" data-bs-toggle="modal" data-bs-target="#editProfile">Edit Profile</button>
                    </div>
                </div>
            </div>
            <div class=" col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo session()->get('name') ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo session()->get('email') ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Mobile</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"><?php echo session()->get('phone_no') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- alerts -->
                <?php if (session()->get('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <?= session()->get('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif (null != $errors) : ?>
                    <div class="alert alert-danger fade show row" role="alert">
                        <div class="col-1"></div>
                        <h5 class="col-9">Fail Input</h5>
                        <ul class="row">
                            <div class="col-1 me-3">
                                <svg class="bi flex-shrink-0" width="24" height="24" role="img" aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                            </div>
                            <div class="col-9">
                                <?php foreach ($errors as $error) : ?>
                                    <li><?= $error; ?></li>
                                <?php endforeach ?>
                            </div>
                        </ul>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profile -->
    <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-<?php echo (null != $errors) ? 'xl' : 'lg'; ?> modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?=
                    form_open('settings/profile');
                    echo csrf_field();
                    ?>
                    <form method="post" action="<?php echo base_url() ?>/settings/profile" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <?php if (null != $errors) : ?>
                                <div class="col-<?php echo (null != $errors) ? '4' : '6'; ?> row">
                                    <?php if (null != $errors) : ?>
                                        <div class="alert alert-danger fade show row" role="alert">
                                            <div class="col-1"></div>
                                            <h5 class="col-9">Fail Input</h5>
                                            <ul>
                                                <?php foreach ($errors as $error) : ?>
                                                    <li><?= $error; ?></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </div>
                                    <?php endif ?>
                                </div>
                            <?php endif ?>
                            <div class="col-<?php echo (null != $errors) ? '4' : '6'; ?> row">
                                <img src="<?php echo old('profile_pic') ?? $profile_pic; ?>" alt="avatar" class="rounded-circle img-fluid" style="min-width: 250px; min-height: 100px;">
                                <div class="form-outline">
                                    <div class="custom-file">
                                        <input name="profile-pic" type="file" class="custom-file-input" id="profile-pic">
                                        <label for="profile-pic">Photo Profile</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-<?php echo (null != $errors) ? '4' : '6'; ?> row">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-outline username">
                                            <input type="text" class="form-control <?php echo $invalid = (isset($errors['username']) || array_key_exists('username', $errors)) ? 'is-invalid' : ''; ?>" id="inputusername" name="username" value="<?php echo old('username', session()->get('username')) ?>">
                                            <label class="form-label" for="inputusername">Username</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-outline name">
                                            <input type="text" class="form-control <?php echo $invalid = (isset($errors['name']) || array_key_exists('name', $errors)) ? 'is-invalid' : ''; ?>" id="inputname" name="name" value="<?php echo old('name', session()->get('name')) ?>">
                                            <label class="form-label" for="inputname">Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-outline email">
                                            <input type="email" class="form-control <?php echo $invalid = (isset($errors['email']) || array_key_exists('email', $errors)) ? 'is-invalid' : ''; ?>" id="inputemail" name="email" readonly value="<?php echo old('email', session()->get('email')) ?>">
                                            <label class="form-label" for="inputemail">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-outline phone_no">
                                            <input type="text" class="form-control <?php echo $invalid = (isset($errors['phone_no']) || array_key_exists('phone_no', $errors)) ? 'is-invalid' : ''; ?>" id="inputphone_no" name="phone_no" value="<?php echo old('phone_no', session()->get('phone_no')) ?>">
                                            <label class="form-label" for="inputphone_no">Mobile</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-outline oldPassword">
                                            <input type="password" class="form-control <?php echo $invalid = (isset($errors['oldPassword']) || array_key_exists('oldPassword', $errors)) ? 'is-invalid' : ''; ?>" id="inputoldPassword" name="oldPassword" value="<?php echo old('oldPassword') ?>">
                                            <label class="form-label" for="inputoldPassword">Old Password</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-outline newPassword">
                                            <input type="password" class="form-control <?php echo $invalid = (isset($errors['newPassword']) || array_key_exists('newPassword', $errors)) ? 'is-invalid' : ''; ?>" id="inputnewPassword" name="newPassword" value="<?php echo old('newPassword') ?>">
                                            <label class="form-label" for="inputnewPassword">New Password</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-outline passwordConfirm">
                                            <input name="passwordConfirm" type="password" id="passwordConfirm" class="form-control <?php echo $invalid = (isset($errors['passwordConfirm']) || array_key_exists('passwordConfirm', $errors)) ? 'is-invalid' : ''; ?>" value="<?php echo old('passwordConfirm') ?>">
                                            <label class="form-label" for="passwordConfirm">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" value="profile">Save changes</button>
                </div>
                </form> <?php echo form_close() ?>
            </div>
        </div>
    </div>

    <script>

    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Validation library file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script>
        // add ajax validation with messages get from controller
        // then add validation to form
    </script>
    <?php d(round(microtime(true) * 1000) - $milliseconds); ?>
    <?= $this->endSection() ?>