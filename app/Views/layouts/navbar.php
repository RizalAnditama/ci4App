<span>
    <!-- <header class="p-3 mb-3 border-bottom">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary">Overview</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Inventory</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Customers</a></li>
                <li><a href="#" class="nav-link px-2 link-dark">Products</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" abineguid="5A8EE7150D364E5F82B1E3824211F676">
                <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
            </form>

            <div class="dropdown text-end">
                <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                </a>
                <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header> -->
</span>

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
                    console.log("Default action of CtrlF")
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

            <!-- Avatar -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hidden-arrow d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo $profile_pic; ?>" class="rounded-circle" height="22" alt="" loading="lazy">
                    <!-- // get profile-pic from database
                    $this->db->select('profile_pic');
                    $this->db->from('users');
                    $this->db->where('username', $this->session->userdata('username'));
                    $query = $this->db->get();
                    $result = $query->row();
                    echo $result->profile_pic; -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink" data-popper-placement="null" data-mdb-popper="none">
                    <li><a class="dropdown-item  <?= $active = ($baseurlprof == $current) ? 'active' : ''; ?>" href="<?php echo base_url('settings/profile') ?>">My profile</a></li>
                    <li><a class="dropdown-item  <?= $active = ($baseurlsettings == $current) ? 'active' : ''; ?>" href="<?php echo base_url('settings') ?>">Settings</a></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <!-- Container wrapper -->
</nav>