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
if (!$userData) {
    header('Location: /account/login?act=logout');
}
$orders = $core->getOrders($_COOKIE['id']);

$cart = (array) json_decode($_COOKIE['cart']);
if (!empty($cart)) {
    $products = ''; $s = '';
    foreach ($cart as $title => $c) {
        $products .= $s.$title;
        $s = ', ';
    }

    $cart = $core->getProductsByIds($products);
} else {
    $cart = false;
}

$notifications = $core->getNotifications($userData['id']);

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
    <title>Личный кабинет</title>
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
                <form method="GET" action="/search" class="ms-auto my-3 d-flex" id="search-form">
                    <a href="/catalog" class="btn btn-primary px-3 border-secondary">Каталог</a>
                    <div class="input-group ms-3">
                        <input type="text" class="form-control" name="query" id="query" autocomplete="off" placeholder="Поиск товара" style="color:#7a7a7a">
                        <button class="btn btn-primary border border-secondary" id="search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
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
                <div class="mb-3">
                    <h2>Личный кабинет</h2>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex flex-column">
                            <div class="bg-white rounded border p-3 mb-3">
                                <h4 class="d-flex align-items-center justify-content-between">
                                    <a href="/account/edit">
                                        <span class="text-dark">Аккаунт</span>
                                    </a>
                                    <a class="text-danger" href="/account/login?act=logout" data-bs-toggle="tooltip" data-bs-placement="top" title="Выйти">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    </a>
                                </h4>
                                <hr>
                                <?php if (isset($userData['first_name']) && isset($userData['last_name'])):?>
                                    <div class="row">
                                        <div class="col-4">Имя</div>
                                        <div class="col-8" style="white-space: nowrap;"><?=$userData['first_name']?> <?=$userData['last_name']?></div>
                                    </div>
                                <?php endif?>
                                <?php if (isset($userData['email'])):?>
                                    <div class="row">
                                        <div class="col-4">Email</div>
                                        <div class="col-8"><?=$userData['email']?></div>
                                    </div>
                                <?php endif?>
                                <div class="mt-1">
                                    <a href="/account/edit">Изменить данные</a>
                                </div>
                                <div class="mt-1">
                                    <a class="text-danger" href="/account/login?act=logout">Выйти</a>
                                </div>
                            </div>
                            <div class="bg-white rounded border p-3 mb-3">
                                <h4 class="d-flex align-items-center">
                                    <a href="/cart">
                                        <span class="text-dark">Корзина</span>
                                    </a>
                                </h4>
                                <hr>
                                <?php if ($cart): $n = 0;?>
                                    <?php foreach ($cart as $product): if ($n === 3) break;?>
                                        <div class="col-12">
                                            <div class="row align-items-center">
                                                <div class="col-3 col-md-2">
                                                    <div class="img-wrapper">
                                                        <img src="/uploads/<?=$product['image']?>" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-9 col-md-10 d-flex flex-column justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div class="fw-bold me-2 row-cut-1"><?=$product['name']?></div>
                                                    </div>
                                                    <div class="text-muted"><?=$product['price']?>₽</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $n = $n + 1; endforeach;?>
                                <?php else:?>
                                    <p class="mb-0">Корзина пуста</p>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="bg-white rounded border p-3 mb-3">
                            <h4 class="d-flex align-items-center">
                                <a href="/account/orders">
                                    <span class="text-dark">Заказы</span>
                                </a>
                            </h4>
                            <hr>
                            <?php if ($orders): $n = 0;?>
                                <div class="row g-2">
                                    <?php foreach ($orders as $order): if ($n === 3) break;?>
                                        <div class="col-12 order-item">
                                            <div class="row align-items-center">
                                                <div class="col-3 col-md-2">
                                                    <div class="img-wrapper">
                                                        <img src="/uploads/<?=$order['preview']?>" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-9 col-md-10 d-flex flex-column justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div class="text-muted me-2" style="white-space: nowrap;">#<?=$order['id']?></div>
                                                        <?=$core->getOrderStatusBadge($order['status'])?>
                                                    </div>
                                                    <div class="fw-bold"><?=$order['price']?>₽</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php $n = $n + 1; endforeach;?>
                                </div>
                            <?php else:?>
                                <p>Заказов ещё нет</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="bg-white rounded border p-3 mb-3">
                            <h4 class="d-flex align-items-center">
                                <span class="text-dark">Уведомления</span>
                            </h4>
                            <hr>
                            <?php if ($notifications):?>
                                <div class="row g-2">
                                    <?php foreach($notifications as $n):?>
                                        <?php $content = (array)json_decode($n['content']);?>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <?php if ($n['type'] === 'LOGIN'):?>
                                                    <h5 class="text-danger">
                                                        <i class="fa-regular fa-right-to-bracket"></i>
                                                    </h5>
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-0">Вход с <?=$content['ip']?></p>
                                                        <small><?=$core->actionDate($n['time'])?></small>
                                                    </div>
                                                <?php elseif ($n['type'] === 'ORDER_CREATION'):?>
                                                     <h5 class="text-danger">
                                                        <i class="fa-regular fa-cart-plus"></i>
                                                    </h5>
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-0">Создан заказ <a href="/account/orders#order-<?=$content['order_id']?>">#<?=$content['order_id']?></a></p>
                                                        <small><?=$core->actionDate($n['time'])?></small>
                                                    </div>
                                                <?php elseif ($n['type'] === 'ACCOUNT_CREATION'):?>
                                                    <h5 class="text-danger">
                                                        <i class="fa-regular fa-user-plus"></i>
                                                    </h5>
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-0">Аккаунт создан</p>
                                                        <small><?=$core->actionDate($n['time'])?></small>
                                                    </div>
                                                <?php elseif ($n['type'] === 'ORDER_PAYMENT'):?>
                                                    <h5 class="text-danger">
                                                        <i class="fa-regular fa-credit-card"></i>
                                                    </h5>
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-0">Заказ #<?=$content['order_id']?> оплачен</p>
                                                        <small><?=$core->actionDate($n['time'])?></small>
                                                    </div>
                                                <?php elseif ($n['type'] === 'ORDER_CONFIRMATION'):?>
                                                    <h5 class="text-danger">
                                                        <i class="fa-regular fa-credit-card"></i>
                                                    </h5>
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-0">Заказ #<?=$content['order_id']?> подтверждён</p>
                                                        <small><?=$core->actionDate($n['time'])?></small>
                                                    </div>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    <?php endforeach;?>
                                </div>
                            <?php else:?>
                                <p>Уведомлений нет</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="text-center text-md-start footer mt-auto" style="background-color: #F0F0F4">
        <div class="container p-4">
            <div class="row justify-content-between text-start py-3">
                <div class="col-lg-5 col-md-12 mb-4 mb-md-0">
                    <p class="fw-bold">Подпишитесь на нашу рассылку и узнавайте об акциях быстрее</p>
                    <div class="d-flex flex-row" style="column-gap: 10px;">
                        <input type="text" class="form-control" placeholder="Ваш e-mail" id="emailSub">
                        <button class="btn btn-primary" id="emailSubBtn">Отправить</button>
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
        let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        $( document ).ready(function() {
           checkout();
        });

        $(".amount-btn").on("click", function(e) {
            e.preventDefault();
            checkout();
        });

        $(".amount").on("change keyup input", function() {
            checkout();
        });

        function checkout() {
            let sum = 0;
            $(".cart-product").each(function(i, item) {
                let price = $(item).find(".price").data("price");
                let amount = $(item).find(".amount").val();

                sum += price * amount;
            });

            sum = Number((sum).toFixed(2));

            $("#sum").text(sum);
        }

        $("#cartItemRemove").on("click", function() {
            let product_id = $("#cartRemoveId").val();

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'removeFromCart',
                    'params': {
                        'product_id': product_id
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        $("#cart-item-"+product_id).remove();
                        $("#cartRemoveConfirmation").modal("toggle");
                        checkout();
                    }
                }
            });
        });

        $(".remove-from-cart").on("click", function(e) {
            e.preventDefault();
            let product_id = $(this).closest(".cart-product").data("id");

            $("#cartRemoveId").val(product_id);

            $("#cartRemoveConfirmation").modal("toggle");
        });

        $("#order").on("click", function() {
            let products = new Array();

            $(".cart-product").each(function(i, item) {
                let temp = {};
                temp.product_id = $(item).data("id");
                temp.amount = parseInt($(item).find(".amount").val());

                products.push(temp);
            });

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'order',
                    'params': {
                        'products': products
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