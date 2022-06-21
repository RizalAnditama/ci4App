<?php
$baseurlmain = basename(base_url('dashboard'));
$baseurlmhs = basename(base_url('mahasiswa'));
$baseurluser = basename(base_url('user'));
$settingurl = basename(base_url('settings'));

$current = basename(current_url());
?>

<style>
    @media (min-width: 991.98px) {
        main {
            padding-left: 240px;
        }
    }

    /* Sidebar */
    #sidebarMenu {
        min-height: 100vh;
    }

    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        padding: 58px 0 0;
        /* Height of navbar */
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
        width: 240px;
        z-index: 600;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            width: 100%;
        }
    }

    .sidebar .active {
        border-radius: 5px;
        box-shadow: 0 2px 5px 0 rgb(0 0 0 / 16%), 0 2px 10px 0 rgb(0 0 0 / 12%);
    }

    .sidebar-sticky {
        position: relative;
        top: 0;
        height: calc(100vh - 48px);
        padding-top: 0.5rem;
        overflow-x: hidden;
        overflow-y: auto;
        /* Scrollable contents if viewport is shorter than content. */
    }
</style>
<!-- Sidebar -->
<nav id="sidebarMenu" class="d-lg-block sidebar bg-white collapse noselect">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a id="dashboard" href="<?= $dashboard = (session()->get('role') === 'admin') ? 'admin' : 'member'; ?>" class="list-group-item list-group-item-action py-2 ripple <?php echo session()->getFlashdata('ye') ?><?= $active = ($baseurlmain === $current || ('admin' === $current) || ('member' === $current)) ? 'active disabled' : ''; ?>" aria-current="true" onclick="dashboardActive()">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main dashboard</span>
            </a>

            <?php if (session()->get('role') === 'admin') : ?>
                <a id="mahasiswa" href="<?= base_url('mahasiswa') ?>" class="list-group-item list-group-item-action py-2 ripple <?= $active = ($baseurlmhs === $current) ? 'active disabled' : ''; ?>" onclick="mahasiswaActive()"><i class="fas fa-chart-bar fa-fw me-3"></i><span>Data Mahasiswa</span>
                </a>

                <a id="user" href="#" class="list-group-item list-group-item-action py-2 ripple <?= $active = ($baseurluser === $current) ? 'active disabled' : ''; ?>" onclick="userActive()"><i class="fas fa-users fa-fw me-3"></i><span>Users</span>
                </a>
            <?php endif; ?>

            <a id="calendar" href="#" class="list-group-item list-group-item-action py-2 ripple" onclick="calendarActive()"><i class="fas fa-calendar fa-fw me-3"></i><span>Calendar</span>
            </a>
        </div>
    </div>
</nav>

<script type="text/javascript">
    // change active menu
    function dashboardActive() {
        document.getElementById('dashboard').classList.add('active');
        document.getElementById('mahasiswa').classList.remove('active');
        document.getElementById('user').classList.remove('active');
        document.getElementById('calendar').classList.remove('active');
    }

    function mahasiswaActive() {
        document.getElementById('dashboard').classList.remove('active');
        document.getElementById('mahasiswa').classList.add('active');
        document.getElementById('user').classList.remove('active');
        document.getElementById('calendar').classList.remove('active');
    }

    function userActive() {
        document.getElementById('dashboard').classList.remove('active');
        document.getElementById('mahasiswa').classList.remove('active');
        document.getElementById('calendar').classList.remove('active');
        document.getElementById('user').classList.add('active');
    }

    function calendarActive() {
        document.getElementById('dashboard').classList.remove('active');
        document.getElementById('mahasiswa').classList.remove('active');
        document.getElementById('user').classList.remove('active');
        document.getElementById('calendar').classList.add('active');
    }
</script>