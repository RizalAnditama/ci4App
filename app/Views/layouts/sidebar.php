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
<nav id="sidebarMenu" class="d-lg-block sidebar bg-white collapse show">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="<?php echo base_url('/dashboard') ?>" class="list-group-item list-group-item-action py-2 ripple <?php echo session()->getFlashdata('ye') ?><?= $active = ($baseurlmain === $current || ('admin' === $current) || ('member' === $current)) ? 'active' : ''; ?>" aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main dashboard</span>
            </a>
            <?php if (session()->get('role') === 'admin') : ?>
                <a href="<?= base_url('mahasiswa') ?>" class="list-group-item list-group-item-action py-2 ripple <?= $active = ($baseurlmhs === $current) ? 'active' : ''; ?>"><i class="fas fa-chart-bar fa-fw me-3"></i><span>Data Mahasiswa</span></a>

                <a href="#" class="list-group-item list-group-item-action py-2 ripple <?= $active = ($baseurluser === $current) ? 'active' : ''; ?>"><i class="fas fa-users fa-fw me-3"></i><span>Users</span></a>
            <?php endif; ?>
            <a href="#" class="list-group-item list-group-item-action py-2 ripple"><i class="fas fa-calendar fa-fw me-3"></i><span>Calendar</span></a>
        </div>
    </div>
</nav>

`