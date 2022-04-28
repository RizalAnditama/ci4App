<?= $this->extend("layouts/app") ?>

<?= $this->section("body") ?>


<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }


    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
</style>

<!-- Custom styles for this template -->
<link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="<?= base_url('templates/bootstrap/blog/blog.css'); ?>" rel="stylesheet">

<!-- Custom styles for this template -->
<link rel="stylesheet" type="text/css" href="<?= base_url('templates/bootstrap/cover/cover.css'); ?>">

<div class="bg-dark min-vh-100">
    <main class="container text-white">
        <section class="d-flex h-100 text-center">
            <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
                <main class="px-3">
                    <h1>Randy School</h1>
                    <p class="lead">Read, Lead, Succeed.</p>
                    <p class="lead">
                        <a href="#" class="btn btn-lg btn-secondary fw-bold border-white bg-white">Learn more</a>
                    </p>
                </main>
            </div>
        </section>
        <div class="row mb-2">
            <div class="col-md-6">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-primary">World</strong>
                        <h3 class="mb-0">Featured post</h3>
                        <div class="mb-1 text-muted">Nov 12</div>
                        <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="stretched-link">Continue reading</a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c" data-darkreader-inline-fill="" style="--darkreader-inline-fill:#43484b;"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em" data-darkreader-inline-fill="" style="--darkreader-inline-fill:#dddad6;">Thumbnail</text>
                        </svg>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <strong class="d-inline-block mb-2 text-success">Design</strong>
                        <h3 class="mb-0">Post title</h3>
                        <div class="mb-1 text-muted">Nov 11</div>
                        <p class="mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="stretched-link">Continue reading</a>
                    </div>
                    <div class="col-auto d-none d-lg-block">
                        <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                            <title>Placeholder</title>
                            <rect width="100%" height="100%" fill="#55595c" data-darkreader-inline-fill="" style="--darkreader-inline-fill:#43484b;"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em" data-darkreader-inline-fill="" style="--darkreader-inline-fill:#dddad6;">Thumbnail</text>
                        </svg>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>



<!-- <div class="container mt-5">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Home</div>
                <div class="panel-body">
                    <h1>
                        <?php
                        $welcome = "Selamat datang" . ' ' . session()->get('username');

                        if (session()->get('role') == "admin") {
                            echo $welcome;
                        } elseif (session()->get('role') == "member") {
                            echo $welcome;
                        } else {
                            echo 'Selamat datang tamu, silahkan login';
                        } ?>
                    </h1>
                    <h3><a href="<?= site_url('login') ?>">Login</a></h3>
                </div>
            </div>
        </div>
    </div>
</div> -->

<?= $this->endSection() ?>