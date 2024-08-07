<?php

require __DIR__.'/../classes/db.class.php';
require __DIR__.'/../classes/main.class.php';

$core = new core();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    if (!$authorized = $core->checkAuth($_COOKIE['id'], $_COOKIE['hash']))
        header('Location: /account/login');
}

if (isset($_GET['id'])) {
    $order = $core->getOrder($_GET['id']);
} else {
    header("Location: /account/orders");
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
    <title>Корзина</title>
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
                                    <i class="fa-regular fa-user me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Войти"></i>
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
                <div class="row d-flex justify-content-center">
                    <div class="col-md-10 col-lg-8 col-xl-7">
                        <div class="card rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between">
                                    <h3 style="white-space: nowrap;">Оплата: <?=number_format($order['price'], 2, ',', ' ')?>₽</h3>
                                    <div class="flex-row align-items-center d-sm-none d-md-flex text-muted" style="column-gap: 8px;">
                                        <div>
                                            <i class="fa-light fa-lock" style="font-size: 26px;"></i>
                                        </div>
                                        <div>
                                            <span>Безопасное<br>соединение</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <span class="text-muted">Заказ №<?=$order['id']?></span>
                                </div>
                                <form action="">
                                    <div class="row mb-3 g-3">
                                        <div class="col-12">
                                            <div class="form-outline">
                                                <input type="text" id="number" class="form-control form-control-lg" placeholder="1234 5678 1234 5678" autocomplete="off" />
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-outline">
                                                <select class="form-select form-select-lg" id="month">
                                                    <option selected disabled hidden>ММ</option>
                                                    <option value="1">01</option>
                                                    <option value="2">02</option>
                                                    <option value="3">03</option>
                                                    <option value="4">04</option>
                                                    <option value="5">05</option>
                                                    <option value="6">06</option>
                                                    <option value="7">07</option>
                                                    <option value="8">08</option>
                                                    <option value="9">09</option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-outline">
                                                <select class="form-select form-select-lg" id="year">
                                                    <option selected disabled hidden>YY</option>
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025">2025</option>
                                                    <option value="2026">2026</option>
                                                    <option value="2027">2027</option>
                                                    <option value="2028">2028</option>
                                                    <option value="2029">2029</option>
                                                    <option value="2030">2030</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-outline">
                                                <input type="password" id="cvv" class="form-control form-control-lg onlyDigits lengthCheck" maxlength="3" placeholder="CVV" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary btn-lg btn-block" id="pay">Оплатить</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="text-center text-md-start footer mt-auto bg-light" style="background-color: #F0F0F4">
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
    <div class="modal fade" id="cartRemoveConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Заказ №<span id="orderIdModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Вы действительно ходите убрать этот товар из корзины?</div>
                <input type="hidden" id="cartRemoveId">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <button id="cartItemRemove" type="button" class="btn btn-success">Да</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
    <script>
        $("#number").on("keydown", function(e) {
            let value = this.value.replace(/\s+/g, '');
            let isBackspace = e.key === 'Backspace'; 

            if ((e.key.length === 1 && /^[^\d\s]+$/.test(e.key)) || (!isBackspace && value.length === 16)) {
                e.preventDefault();
                return false;
            }

            this.value = value.split('').reverse().join('').replace(/\B(?=(\d{4})+(?!\d))/g, " ").split('').reverse().join('').trim();
        });

        $("#cvv").on("input keyup", function() {
            if ($(this).val().length > 2)
                return false;
        });

        $(".onlyDigits").on("input", function() {
            let node = $(this);
            node.val(node.val().replace(/[^0-9]/ig,''));
        });

        $(".lengthCheck").on("input paste", function() {
            let $this = $(this);
            let val = $this.val();
            let valLength = val.length;
            let maxCount = $this.attr('maxlength');
            if (valLength>maxCount){
                $this.val($this.val().substring(0,maxCount));
            }
        });
  
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $(".amount").on("change keyup input", function() {
            checkout();
        });

        $("#pay").on("click", function() {
            let number = $("#number").val();
            if (!number)
                return $("#number").focus();

            let month = $("#month").val();
            if (!month)
                return $("#month").focus();

            let year = $("#year").val();
            if (!year)
                return $("#year").focus();

            let cvv = $("#cvv").val();
            if (!cvv)
                return $("#cvv").focus();

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'orderPay',
                    'params': {
                        'order_id': <?=$_GET['id']?>,
                        'user_id': <?=$userData['id']?>
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        window.location.href = "/account/orders";
                    }
                }
            });
        });
    </script>
</body>
</html>