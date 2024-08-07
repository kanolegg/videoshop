<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

$cart = (array) json_decode($_COOKIE['cart']);
if (!empty($cart)) {
    $products = ''; $s = '';
    foreach ($cart as $title => $c) {
        $products .= $s.$title;
        $s = ', ';
    }

    $products = $core->getProductsByIds($products);
} else {
    $products = false;
}

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $authorized = $core->checkAuth($_COOKIE['id'], $_COOKIE['hash']);
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
                                <i class="fa-solid fa-wand-magic-sparkles text-muted"></i>
                                <a class="text-muted" href="/smart">Умный дом</a>
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
                            <h3 class="fw-normal mb-0 text-black">Корзина</h3>
                        </div>
                        <form>
                            <?php if($products):?>
                                <?php foreach ($products as $p):?>
                                    <div id="cart-item-<?=$p['id']?>" class="card rounded-3 mb-3 cart-product" data-id="<?=$p['id']?>">
                                        <div class="card-body p-4">
                                            <div class="row d-flex justify-content-between align-items-center">
                                                <div class="col-md-2 col-lg-2 col-xl-2 d-flex justify-content-center">
                                                    <img src="<?php if (isset($p['image'])):?>/uploads/<?=$p['image']?><?php else:?>/assets/img/noimg.png<?php endif;?>" class="img-fluid rounded-3" alt="Cotton T-shirt" style="max-height: 12rem;">
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-3">
                                                    <a href="/product/<?=$p['id']?>">
                                                        <p class="lead fw-normal mb-2 row-cut-2" title="<?=htmlspecialchars($p['name'])?>"><?=htmlspecialchars($p['name'])?></p>
                                                    </a>
                                                    <p><span class="text-muted">Арт: </span><?=$p['article']?></p>
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                    <button class="btn btn-link px-2 amount-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input min="0" name="amount" value="<?=$cart[$p['id']]?>" type="number" class="form-control form-control-sm amount" />
                                                    <button class="btn btn-link px-2 amount-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                    <h5 class="mb-0">
                                                        <span class="price" data-price="<?=$p['price']?>"><?=$p['price']?></span>₽
                                                    </h5>
                                                </div>
                                                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                    <a href="#!" class="text-danger remove-from-cart">
                                                        <i class="fas fa-trash fa-lg"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            <?php else:?>
                                <p class="mb-1">Вы ещё ничего не добавили в свою корзину,</p>
                                <p>самое время выбрать что-то из <a href="/catalog">каталога</a></p>
                            <?php endif;?>
                        </form>
                        <?php if ($products):?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="sum-wrapper">
                                            <p class="small text-muted mb-0 pb-0">Сумма</p>
                                            <p class="lead fw-normal mb-0">
                                                <span id="sum">0</span>₽
                                            </p>
                                        </div>
                                        <?php if ($authorized):?>
                                                <button type="button" class="btn btn-warning btn-block btn-lg" id="order" style="box-shadow: 0 4px 9px -4px rgba(0,0,0,0.35);">Отправить</button>
                                        <?php else:?>
                                             <a href="/account/login?redirect=/cart" class="btn btn-warning btn-block btn-lg" id="order" style="box-shadow: 0 4px 9px -4px rgba(0,0,0,0.35);">Отправить</a>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
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
    <div class="modal fade" tabindex="-1" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Корзина</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Товар удалён из корзины!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Продолжить покупки</button>
                    <a href="/cart" class="btn btn-warning">Корзина</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cartRemoveConfirmation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Корзина</h5>
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
            let products = {};

            $(".cart-product").each(function(i, item) {
                products[$(item).data("id")] = parseInt($(item).find(".amount").val());
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
                    // return console.log(response)
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