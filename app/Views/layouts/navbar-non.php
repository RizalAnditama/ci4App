<?php
$baseurlhome = basename(base_url('home'));
$baseurllogin = basename(base_url('login'));
$baseurlregister = basename(base_url('register'));

$current = basename(current_url());
?>

<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="https://i.imgur.com/cl0qVur.jpg" alt="Logo Rizalandit" width="50px" class="me-3">
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a id="home" href="<?= base_url('home'); ?>" class="nav-link px-2 text-<?= $inactive = ($baseurlhome != $current) ? 'white' : 'secondary'; ?>" onclick="homeActive()">Home</a></li>
                <li><a href="#" class="nav-link px-2 text-white">About</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Education</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Acceptance</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Research</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Devotion</a></li>
                <li><a href="#" class="nav-link px-2 text-white">Innovation</a></li>
            </ul>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" abineguid="81E5C2DE95D84EFD85F890BAB19EA671">
                <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
            </form>

            <div class="text-end">
                <a id="login" href="<?php echo base_url('login') ?>" class="btn btn-<?= $inactive = ($baseurllogin != $current) ? 'outline-light' : 'primary'; ?> me-2" onclick="loginActive()">Login</a>

                <a id="register" href="<?php echo base_url('register') ?>" class="btn btn-<?= $inactive = ($baseurlregister != $current) ? (($baseurllogin == $current) ? 'outline-light' : 'secondary') : 'primary'; ?>" onclick="registerActive()">Register</a>
            </div>
        </div>
    </div>
</header>

<script type="text/javascript">
    function homeActive() {
        var home = document.getElementById("home");
        home.classList.remove("text-white");
        home.classList.add("text-secondary");

        var login = document.getElementById("login");
        login.classList.remove("btn-primary");
        login.classList.add("btn-outline-light");

        var register = document.getElementById("register");
        register.classList.remove("btn-primary");
        register.classList.add("btn-secondary");
    }

    function loginActive() {
        var home = document.getElementById("home");
        home.classList.remove("text-secondary");
        home.classList.add("text-white");

        var login = document.getElementById("login");
        login.classList.remove("btn-outline-light");
        login.classList.add("btn-primary");

        var register = document.getElementById("register");
        register.classList.remove("btn-primary");
        register.classList.remove("btn-secondary");
        register.classList.add("btn-outline-light");
    }

    function registerActive() {
        var home = document.getElementById("home");
        home.classList.remove("text-secondary");
        home.classList.add("text-white");

        var login = document.getElementById("login");
        login.classList.remove("btn-primary");
        login.classList.add("btn-outline-light");

        var register = document.getElementById("register");
        register.classList.remove("btn-outline-light");
        register.classList.remove("btn-secondary");
        register.classList.add("btn-primary");
    }

    function forgotPass() {
        var home = document.getElementById("home");
        home.classList.remove("text-secondary");
        home.classList.add("text-white");

        var login = document.getElementById("login");
        login.classList.remove("btn-primary");
        login.classList.add("btn-outline-light");

        var register = document.getElementById("register");
        register.classList.remove("btn-primary");
        register.classList.add("btn-secondary");

        var forgotpass = document.getElementById("forgotpass");
        forgotpass.classList.remove("text-white");
        forgotpass.classList.add("text-secondary");
    }
</script>