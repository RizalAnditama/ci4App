<?php

use App\Models\UserModel;

$baseurlprof = basename(base_url('profile'));
$baseurlsettings = basename(base_url('settings'));
$current = basename(current_url());

$this->userModel = new UserModel();
$profile_pic = base_url() . '/' . $this->userModel->getProfilePic(session()->get('id_user'));
?>

<script>
    $(document).ready(function() {

        window.addEventListener("keydown", function(e) {
            if (e.keyCode === 191) {
                if ($('#search').is(":focus")) {
                    console.log("Default action of Ctrl + /")
                    return true;
                } else {

                    e.preventDefault();
                    console.log("Search is not in focus");
                    $('#search').focus();
                }
            }
        })

    })
</script>

<nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
    <!-- Container wrapper -->
    <div class="container-fluid">
        <!-- Toggle button -->
        <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" href="#">
            <img src="https://i.imgur.com/cl0qVur.jpg" height="25" alt="" loading="lazy">
        </a>
        <!-- Search form -->
        <form class="d-none d-md-flex input-group w-auto my-auto" abframeid="iframe.0.9471717373283652" abineguid="2A614043E0DC40D28FE0AC4D0A0F9175">
            <input id="search" autocomplete="off" type="search" class="form-control rounded" placeholder="Search (ctrl + &quot;/&quot; to focus)" style="min-width: 225px">
            <span class="input-group-text border-0"><i class="fas fa-search"></i></span>
        </form>

        <!-- Right links -->
        <ul class="navbar-nav ms-auto d-flex flex-row">
            <!-- Notification dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link me-3 me-lg-0 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <!-- <span class="badge rounded-pill badge-notification bg-danger">1</span> -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="#">Some news</a></li>
                    <li><a class="dropdown-item" href="#">Another news</a></li>
                    <li>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </li>
                </ul>
            </li>
            <!-- Icon Github -->
            <li class="nav-item me-3 me-lg-0">
                <a class="nav-link" href="https://github.com/RizalAnditama/ci4App" target="_blank">
                    <i class="fab fa-github"></i>
                </a>
            </li>

            <!-- Profile -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $profile_pic; ?>" class="rounded-circle" height="22" alt="" loading="lazy">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink" data-popper-placement="null" data-mdb-popper="none">
                    <li><a class="dropdown-item  <?= $active = ($baseurlprof == $current) ? 'active disabled' : ''; ?>" href="<?php echo base_url('settings/profile') ?>"><i class="bi bi-person-circle"></i> My profile</a></li>
                    <li><a class="dropdown-item  <?= $active = ($baseurlsettings == $current) ? 'active disabled' : ''; ?>" href="<?php echo base_url('settings') ?>"><i class="bi bi-gear-fill"></i> Settings</a></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Container wrapper -->
</nav>