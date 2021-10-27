<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>test account</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" crossorigin="anonymous"></script>

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css" />

    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body>

<div style="position: relative">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light NavbarCustom" id="mainNav">
        <div class="container NavbarCustom-container px-4 px-lg-5">
            <a class="navbar-brand Navbar-logo" href="index.php?p=home"><img src="assets/img/logo2white.png" alt="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav py-4 py-lg-0">
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="index.php?p=home">Home</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="#">Sample Post</a></li>
                    <li class="nav-item"><a class="nav-link px-lg-3 py-3 py-lg-4" href="#">Contact</a></li>
                </ul>
                <div class="py-4 py-lg-0 ms-auto">
                    <!-- partie modifiée -->
                    <?php use App\Model\Session;

                    if(isset($_SESSION['auth'])): ?>
                        <a href="index.php?p=logout" class="btn btn-primary">Se déconnecter</a>
                    <?php else: ?>
                        <a href="index.php?p=login" class="btn btn-primary">Login</a>
                        <a href="index.php?p=register" class="btn btn-primary">Créer un compte</a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header-->
    <header class="masthead" style="background-image: url('assets/img/test-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-10 col-xl-10">
                    <div class="site-heading">
                        <h1 class="subheading">Une phrase qui accroche</h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!---------------------- partie modifiée ---------------------->
    <?php
    if(Session::getInstance()->hasFlashes()) : ?>
        <?php foreach (Session::getInstance()->getFlashes() as $type => $message) : ?>
            <div class="alert alert-<?= $type; ?>">
                <?= $message ?>
            </div>
        <?php endforeach ?>
    <?php endif; ?>
