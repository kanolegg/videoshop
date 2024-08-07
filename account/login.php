<?php

require __DIR__.'/../classes/db.class.php';
require __DIR__.'/../classes/main.class.php';

$core = new core();

if (isset($_GET['act']) && $_GET['act'] === 'logout') {
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
        $core->logout($_COOKIE['id'], $_COOKIE['hash']);
        header('Location: /account/login');
    }
}

if (isset($_COOKIE['id']) && isset($_COOKIE['hash'])) {
    if ($authorized = $core->checkAuth($_COOKIE['id'], $_COOKIE['hash'])) 
        if (isset($_GET['redirect']))
            header('Location: '.$_GET['redirect']);
        else
            header('Location: /');
}

$state = null;

if (isset($_POST['login'])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        if ($userData = $core->getUser($email)) {
            if ($core->isValidPassword($password, $userData['password'])) {
                $hash = $core->generateHash(10);
                $core->sessionCreate($userData['id'], $hash, ip2long($_SERVER['REMOTE_ADDR']));
                $core->notificationCreate($userData['id'], json_encode(['ip'=>$_SERVER['REMOTE_ADDR']]), 'LOGIN');

                setcookie('id', $userData['id'], $_SERVER['REQUEST_TIME']+60*60*24*30, '/');
                setcookie('hash', $hash, $_SERVER['REQUEST_TIME']+60*60*24*30, '/');

                if ($userData['rank'] == 1)
                    return header('Location: /admin/');
                
                if (isset($_GET['redirect']))
                    return header('Location: '.$_GET['redirect']);

                return header('Location: /');
            } else {
                $state = [
                    'error' => true,
                    'message' => 'Неправильный пароль'
                ];
            }
        } else {
            $state = [
                'error' => true,
                'message' => 'Пользователь не найден'
            ];
        }
    }
}

?>

<!DOCTYPE html>
<html class="h-100">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="">
  <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css?=<?=$_SERVER['REQUEST_TIME']?>">
  <link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
  <title>Авторизация</title>
</head>
<body class="d-flex flex-column">
    <header class="shadow-sm">
            <div class="superNav border-bottom py-2 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 centerOnMobile">
                            <span class="me-3">
                                <i class="fa-solid fa-phone me-1 text-primary"></i>
                                <strong>8-800-546-4536</strong>
                            </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-none d-lg-block d-md-block-d-sm-block d-xs-none text-end">
                            <span class="me-3">
                                <i class="fa-solid fa-truck text-muted me-1"></i>
                                <a class="text-muted" href="/delivery">Доставка</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg bg-white sticky-top navbar-light p-3 shadow-sm">
                <div class="container justify-content-between">
                    <a class="navbar-brand" href="/">
                        <img height="50" src="/assets/img/logo.svg">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <form method="GET" action="/search" class="ms-auto my-3 d-flex" id="search-form">
                        <div class="input-group ms-3">
                            <input type="text" class="form-control" name="query" id="query" autocomplete="off" placeholder="Поиск товара" style="color:#7a7a7a">
                            <button class="btn btn-primary border border-secondary" id="search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                    <div class="d-none d-md-block ms-5 text-center">Интернет-магазин<br>интеллектуальных систем<br>безопасности</div>
                    <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                        <ul class="navbar-nav ms-auto ">
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-uppercase" href="/favourites"> <i class="fa-regular fa-heart me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Избранное"></i> <span class="d-md-none">Избранное</span> </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-2 text-uppercase" href="/cart"> <i class="fa-regular fa-cart-shopping me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Корзина"></i> <span class="d-md-none">Корзина</span> </a>
                            </li>
                            <li class="nav-item">
                                <?php if (!$authorized):?>
                                    <a class="nav-link mx-2 text-uppercase" href="/account/login">
                                        <i class="fa-regular fa-user me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Войти"></i>
                                        <span class="d-md-none">Войти</span>
                                    </a>
                                <?php else:?>
                                    <a class="nav-link mx-2 text-uppercase" href="/account/profile">
                                        <i class="fa-regular fa-user me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Профиль"></i>
                                        <span class="d-md-none">Профиль</span>
                                    </a>
                                <?php endif;?>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    <main>
        <section class="py-3">
            <div class="container">
                <div class="d-flex justify-content-center">
                    <form method="POST" style="width:22rem">
                        <div class="text-center">
                            <h2>Авторизация</h2>
                        </div>
                        <?php if ($state):?>
                            <?php if ($state['error']):?>
                                <div class="alert alert-danger"><?=$state['message']?></div>
                            <?php else:?>
                                <div class="alert alert-success"><?=$state['message']?></div>
                            <?php endif;?>
                        <?php endif;?>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" autofocus id="email" name="email" class="form-control" />
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">Пароль</label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block mb-4">Войти</button>
                        <div class="text-center">
                            <p>Впервые у нас? <a href="/account/register<?=($_GET['redirect'])?'?redirect='.$_GET['redirect']:''?>">Регистрация</a></p>
                        </div>
                    </form>
                </div>
                
            </div>
        </section>
    </main>
    <footer class="text-center text-md-start mt-auto" style="background-color: #F0F0F4">
        <div class="container p-4">
            <div class="row text-start py-3">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <p class="fw-bold">Подпишитесь на нашу рассылку и узнавайте об акциях быстрее</p>
                    <div class="d-flex flex-row">
                        <div class="mb-1">
                            <label for="emailSubscribe" class="visually-hidden">Password</label>
                            <input type="email" class="form-control" id="emailSubscribe" placeholder="Ваш e-mail"> </div>
                        <div class="ms-2 mb-1">
                            <button type="submit" class="btn btn-primary mb-3">Отправить</button>
                        </div>
                    </div>
                </div>
                <div class="text-start col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold">Информация</h5>
                    <ul class="list-unstyled mb-0">
                        <li> <a href="#!" class="text-dark">О компании</a> </li>
                        <li> <a href="#!" class="text-dark">Контакты</a> </li>
                        <li> <a href="#!" class="text-dark">Акции</a> </li>
                    </ul>
                </div>
                <div class="text-start col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold">Магазин</h5>
                    <ul class="list-unstyled">
                        <li> <a href="#!" class="text-dark">Доставка</a> </li>
                        <li> <a href="#!" class="text-dark">Оплата</a> </li>
                        <li> <a href="#!" class="text-dark">Новости</a> </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">2023 © Copyright, Security </div>
    </footer>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
</body>
</html>