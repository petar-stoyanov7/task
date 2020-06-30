<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/main.css">
    <?php if (isset($cssArray) && count($cssArray) > 0) : ?>
        <?php foreach($cssArray as $cssFile) : ?>
            <link rel="stylesheet" href="/css/<?= $cssFile ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <title>
        <?= isset($title) ? $title : 'Галерия'; ?>
    </title>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">Gallery</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item<?= $_SERVER['REQUEST_URI'] === '/' ? ' active' : '' ?>">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item<?= $_SERVER['REQUEST_URI'] === '/pictures' ? ' active' : '' ?>">
                    <a class="nav-link" href="/pictures">Pictures</a>
                </li>
                <li class="nav-item<?= $_SERVER['REQUEST_URI'] === '/users' ? ' active' : '' ?>">
                    <a class="nav-link" href="/users">Users</a>
                </li>
                <li class="nav-item<?= $_SERVER['REQUEST_URI'] === '/contacts' ? ' active' : '' ?>">
                    <a class="nav-link" href="/contacts">Contacts</a>
                </li>
                <?php if (!isset($_SESSION['user']) && $_SESSION['user']['group'] === 'admin') : ?>
                    <li class="nav-item<?= $_SERVER['REQUEST_URI'] === '/admin' ? ' active' : '' ?>">
                        <a class="nav-link" href="/admin">Admin panel</a>
                    </li>
                <?php endif; ?>

            </ul>
            <div class="my-2 my-lg-0">
                <?php if (isset($_SESSION['user'])) : ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Профил</a>
                    </li>
                </ul>
                <?php else : ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link login-button" href="/account/login">Вход</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link register-button" href="#">Регистрация</a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<div id="black-lvl-1" class="site-overlay"></div>
<div id="black-lvl-2" class="site-overlay"></div>
<div id="black-lvl-3" class="site-overlay"></div>