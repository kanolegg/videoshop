<?php

require __DIR__.'/../classes/db.class.php';
require __DIR__.'/../classes/main.class.php';

$core = new core();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    if (!$authorized = $core->checkAuth($_COOKIE['id'], $_COOKIE['hash']))
        header('Location: /account/login');
} else {
    header('Location: /account/login');
}

$userData = $core->getUser($_COOKIE['id']);

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
    <title>Управление аккаунтом</title>
</head>
<body class="d-flex flex-column h-100">
    <header class="shadow-sm d-table">
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
                        <span class="me-3">
                            <i class="fa-solid fa-file  text-muted me-2"></i>
                            <a class="text-muted" href="/about">О нас</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg bg-white sticky-top navbar-light p-3 shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img height="50" src="/assets/img/logo.svg">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="ms-auto my-3 d-flex">
                    <a href="/catalog" class="btn btn-secondary border-secondary text-white">Каталог</a>
                    <div class="input-group ms-3">
                        <input type="text" class="form-control border-secondary" autocomplete="off" placeholder="Поиск товара" style="color:#7a7a7a">
                        <button class="btn btn-secondary border-secondary text-white"> <i class="fa-solid fa-magnifying-glass"></i> </button>
                    </div>
                </div>
                <div class=" collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto ">
                        <li class="nav-item">
                            <a class="nav-link mx-2 text-uppercase" href="/favourites"> <i class="fa-regular fa-heart me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Избранное"></i> <span class="d-md-none">Избранное</span> </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 text-uppercase" href="/cart">
                                <i class="fa-regular fa-cart-shopping me-md-3 position-relative" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Корзина">
                                    <?php if ($cart):?>
                                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                            <span class="visually-hidden">В корзине есть товары</span>
                                        </span>
                                    <?php endif;?>
                                </i>
                                <span class="d-md-none">Корзина</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <?php if (!$authorized):?>
                                <a class="nav-link mx-2 text-uppercase" href="/account/login">
                                    <i class="fa-regular fa-user me-md-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Войти"></i>
                                    <span class="d-md-none">Войти</span>
                                </a>
                            <?php else:?>
                                <a class="nav-link mx-2 text-uppercase" href="/account/profile">
                                    <i class="fa-regular fa-user me-md-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Профиль"></i>
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
                <div class="my-2 px-3 rounded border bg-white">
                    <h2 class="my-3">Аккаунт</h2>
                </div>
                <div class="bg-white rounded border p-3 mb-3">
                    <h4>Учётная запись</h4>
                    <div id="account-alert"></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <input type="text" id="first_name" value="<?=$userData['first_name']?>" placeholder="Имя" class="form-control form-control-lg onlyLetters account-field" />
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="last_name" value="<?=$userData['last_name']?>" placeholder="Фамилия" class="form-control form-control-lg onlyLetters account-field" />
                        </div>
                        <div class="col-md-6">
                            <input type="email" id="email" value="<?=$userData['email']?>" placeholder="Email" class="form-control form-control-lg account-field" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" disabled id="account-update">Сохранить</button>
                    </div>
                </div>
                <div class="bg-white rounded border p-3">
                    <h4>Пароль</h4>
                    <div id="password-alert"></div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <input type="password" id="password_old" placeholder="Старый пароль" class="form-control form-control-lg password-field" />
                        </div>
                        <div class="col-md-6">
                            <input type="password" id="password_new" placeholder="Новый пароль" class="form-control form-control-lg password-field" />
                        </div>
                        <div class="col-md-6">
                            <input type="password" id="password_confirm" placeholder="Подтверждение пароля" class="form-control form-control-lg password-field" />
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" disabled id="password-update">Обновить</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="text-center text-md-start footer mt-auto" style="background-color: #F0F0F4">
        <div class="container p-4">
            <div class="row text-start py-3">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <p class="fw-bold">Подпишитесь на нашу рассылку и узнавайте об акциях быстрее</p>
                    <div class="d-flex flex-row" style="column-gap: 10px;">
                        <input type="text" class="form-control" placeholder="Ваш e-mail" id="emailSubscribe">
                        <button class="btn btn-primary">Отправить</button>
                    </div>
                </div>
                <div class="text-start col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold">Информация</h5>
                    <ul class="list-unstyled mb-0">
                        <li> <a href="#!" class="text-dark">Telegram</a> </li>
                        <li> <a href="#!" class="text-dark">Viber</a> </li>
                    </ul>
                </div>
                <div class="text-start col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="fw-bold">Магазин</h5>
                    <ul class="list-unstyled">
                        <li> <a href="/delivery" class="text-dark">Доставка</a> </li>
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
    <script>
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $(".onlyLetters").on("input", function() {
            let node = $(this);
            node.val(node.val().replace(/[^a-zа-яё ]/ig,''));
        });

        $(".account-field").on("input paste change", function() {
            $("#account-update").removeAttr("disabled");
        });

        $(".password-field").on("input paste change", function() {
            $("#password-update").removeAttr("disabled");
        });

        $("#account-update").on("click", function() {
            $("#account-alert").empty();
            let btn = $(this);
            btn.attr("disabled", true);

            let first_name = $("#first_name").val().trim();
            let last_name = $("#last_name").val().trim();
            let email = $("#email").val().trim();

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'accountUpdate',
                    'user_id': <?=$userData['id']?>,
                    'params': {
                        'last_name': last_name,
                        'first_name': first_name,
                        'email': email
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        btn.removeAttr("disabled");
                        $(".alert-field").val("");
                        toggleAlert('account-alert', 'alert-success', response.message);
                    } else {
                        toggleAlert('account-alert', 'alert-danger', response.message);
                    }
                }
            });
        });

        $("#password-update").on("click", function() {
            $("#password-alert").empty();
            let btn = $(this);
            btn.attr("disabled", true);

            let password_old = $("#password_old").val().trim();
            let password_new = $("#password_new").val().trim();
            let password_confirm = $("#password_confirm").val().trim();

            if (password_new !== password_confirm) {
                btn.removeAttr("disabled");
                return toggleAlert('password-alert', 'alert-danger', 'Пароли не совпадают');
            }

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'passwordUpdate',
                    'params': {
                        'user_id': <?=$userData['id']?>,
                        'password_old': password_old,
                        'password_new': password_new
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        btn.removeAttr("disabled");
                        $(".password-field").val("");
                        toggleAlert('password-alert', 'alert-success', response.message);
                    } else {
                        toggleAlert('password-alert', 'alert-danger', response.message);
                    }
                }
            });
        });
        function toggleAlert(alert, type, text) {
            $("#"+alert).empty();
            let a = jQuery('<div>', {
                'class': 'alert-dismissible fade show alert ' + type,
                'role': 'alert'
            }).text(text).appendTo($("#"+alert));

            jQuery('<div>', {
                'class': 'btn-close',
                'type': 'button',
                'data-bs-dismiss': 'alert',
                'aria-label': 'Close'
            }).appendTo($(a));
        }
    </script>
</body>
</html>