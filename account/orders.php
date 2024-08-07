<?php

require __DIR__.'/../classes/db.class.php';
require __DIR__.'/../classes/main.class.php';

$core = new core();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    if (!$authorized = $core->checkAuth($_COOKIE['id'], $_COOKIE['hash']))
        header('Location: /account/login');
}

$userData = $core->getUser($_COOKIE['id']);
$orders = $core->getOrders($_COOKIE['id']);

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
    <title>Заказы</title>
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
                <div class="row d-flex justify-content-center align-items-center">
                    <div class="col-12 col-md-10">
                        <div class="mb-3">
                            <h3 class="fw-normal mb-0 text-black">Заказы</h3>
                        </div>
                        <?php if ($orders):?>
                            <?php foreach ($orders as $order):?>
                                <div class="order" id="order-<?=$order['id']?>" data-order="<?=htmlspecialchars(json_encode($order, JSON_UNESCAPED_UNICODE))?>">
                                    <div class="mb-1">
                                        <div class="text-muted"><?=$core->actionDate($order['time'])?></div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body p-4">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="img-wrapper">
                                                        <img src="/uploads/<?=$order['preview']?>" class="img-fluid">
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 d-flex flex-column justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div class="text-muted me-2" style="white-space: nowrap;">Номер заказа: #<?=$order['id']?></div>
                                                        <?=$core->getOrderStatusBadge($order['status'])?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <h4 class="fw-bold" style="white-space: nowrap;"><?=number_format($order['price'], 0, ',', ' ')?>₽</h4>
                                                    <div class="mt-1">
                                                        <?php if ($order['status'] === 0):?>
                                                            <div class="d-flex flex-lg-column" id="buttons-wrapper" style="column-gap: 8px; grid-row-gap: 8px;">
                                                                <a href="/account/payment/<?=$order['id']?>" class="btn btn-warning rounded-3">К оплате</a>
                                                                <button type="button" data-id="<?=$order['id']?>" class="btn btn-danger rounded-3 cancel-order">Отменить</button>
                                                            </div>
                                                        <?php elseif ($order['status'] === 2):?>
                                                            <button type="button" data-id="<?=$order['id']?>" class="btn btn-primary rounded-3 receive">Получено</button>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>
                        <?php else:?>
                            <p>Заказов ещё нет</p>
                        <?php endif;?>
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
    <div class="modal fade" id="cancel-order-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение<span id="orderIdModal"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Вы действительно хотите отменить этот заказ?</div>
                <input type="hidden" id="cancel-order-id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Нет</button>
                    <button id="cancel-order-confirm" type="button" class="btn btn-secondary">Да</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="receive-order-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Информация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Вы подтвердили получение заказа</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
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

        $("#search").on("click", function() {
            let query = $("#query").val().trim();
            if (query === "")
                return false;
        });

        $(".cancel-order").on("click", function() {
            let id = $(this).data("id");
            $("#cancel-order-id").val(id);
            $("#cancel-order-modal").modal("toggle");
        });

        $("#cancel-order-confirm").on("click", function() {
            let id = $("#cancel-order-id").val();
            let wrapper = $("#order-"+id);

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'cancelOrder',
                    'params': {
                        'id': id
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        $(wrapper).find("#status-badge").removeClass("bg-info");
                        $(wrapper).find("#status-badge").addClass("bg-danger");
                        $(wrapper).find("#status-badge").text("Отменён");
                        $(wrapper).find("#buttons-wrapper").remove();
                        $("#cancel-order-modal").modal("toggle");
                    }
                }
            });
        });

        $(".receive").on("click", function() {
            let id = $(this).data("id");
            let wrapper = $("#order-"+id);

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'orderStatusUpdate',
                    'params': {
                        'status': 3,
                        'user_id': <?=$userData['id']?>,
                        'order_id': id
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        $(wrapper).find("#status-badge").removeClass("bg-info");
                        $(wrapper).find("#status-badge").addClass("bg-success");
                        $(wrapper).find("#status-badge").text("Получен");
                        $(wrapper).find("#buttons-wrapper").remove();
                        $("#receive-order-modal").modal("toggle");
                    }
                }
            });
        });
    </script>
</body>
</html>