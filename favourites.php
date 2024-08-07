<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

$favourites = (array) json_decode($_COOKIE['favourites']);
if (!empty($favourites)) {
    $products = ''; $s = '';
    foreach ($favourites as $c) {
        $products .= $s.$c;
        $s = ', ';
    }

    $products = $core->getProductsByIds($products);

    $cart = (array)json_decode($_COOKIE['cart']);
    $cart = (!empty($cart)) ? $cart : [];

    $userData = $core->getUser($_COOKIE['id']);
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
	<title>Избранное</title>
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
                            <h3 class="fw-normal mb-0 text-black">Избранное</h3>
                        </div>
                        <?php if ($products):?>
                            <div class="row">
                                <?php foreach($products as $p):?>
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="border rounded-1 shadow-sm product-card bg-white">
                                            <div class="m-3 mb-0 d-flex flex-column align-items-center">
                                                <div class="text-center position-relative w-100">
                                                    <div class="product-card-like position-absolute<?=in_array($p['id'], $favourites) ? ' liked' : ''?>">
                                                        <a class="<?=in_array($p['id'], $favourites) ? 'dislike' : 'like'?>" data-id="<?=$p['id']?>" href="#">
                                                            <i class="fa-solid fa-heart"></i>
                                                        </a>
                                                    </div>
                                                    <a href="/product/<?=$p['id']?>">
                                                        <img src="/uploads/<?=$p['image']?>" class="img-fluid">
                                                    </a>
                                                        
                                                </div>
                                                <div class="my-3">
                                                    <div class="text-center">
                                                        <a href="/product/<?=$p['id']?>">
                                                            <div class="text-muted"><?=$p['name']?></div>
                                                        </a>
                                                        <h4 class="fw-bold price"><?=$p['price']?>₽</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bottom text-end item-buttons">
                                                <?php if (!in_array($p['id'], $cart)):?>
                                                    <button class="product-card-cart bg-primary text-white addToCart" data-id="<?=$p['id']?>">
                                                        <i class="fa-regular fa-cart-shopping"></i>
                                                    </button>
                                                <?php else:?>
                                                    <a href="/cart" class="product-card-cart bg-warning text-white">
                                                        <i class="fa-regular fa-cart-shopping"></i>
                                                    </a>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        <?php else:?>
                            <p>Вы ещё не добавили ни один товар в избранное</p>
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
                    <p>Товар добавлен в корзину!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Продолжить покупки</button>
                    <a href="/cart" class="btn btn-warning">Корзина</a>
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

        $(".search").click(function(e) {
            if ($(this).closest(".input-group").find("input").val() == "") {
                e.preventDefault();
            }
        });

        $(".product-card-like").on("click", ".like", function(e) {
            e.preventDefault();

            let id = $(this).data("id");
            let btn = $(this);

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'productLike',
                    'params': {
                        'product_id': id
                    }
                },
                success: function(response) {
                    console.log(response)
                    response = JSON.parse(response);
                    
                    if (!response.error) {
                        btn.removeClass("like");
                        btn.addClass("dislike");
                        return btn.closest(".product-card-like").addClass("liked");
                    }

                    return console.log(response.message);
                }
            });
        });

        $(".product-card-like").on("click", ".dislike", function(e) {
            e.preventDefault();

            let id = $(this).data("id"); let favourites;
            let btn = $(this)

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'productDislike',
                    'params': {
                        'product_id': id
                    }
                },
                success: function(response) {
                    console.log(response)
                    response = JSON.parse(response);
                    
                    if (!response.error) {
                        btn.removeClass("dislike");
                        btn.addClass("like");
                        return btn.closest(".product-card-like").removeClass("liked");
                    }

                    return console.log(response.message);
                }
            });
        });

        $(".addToCart").on("click", function() {
            let id = $(this).data("id");

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'addToCart',
                    'params': {
                        'product_id': id
                    }
                },
                success: function(response) {
                    console.log(response)
                    response = JSON.parse(response);
                    if (!response.error) {
                        $("#cartModal").modal("toggle");
                        let wrapper = $(this).closest(".item-buttons");
                        $(wrapper).empty();

                        let button = jQuery('<a>', {
                            class: 'product-card-cart bg-warning text-white',
                            href: '/cart'
                        }).appendTo(wrapper);

                        jQuery('<i>', {
                            class: 'fa-regular fa-cart-shopping'
                        }).appendTo(button);
                    }
                }
            });
        });

        $(".added").on("click", function() {
            window.location.href = '/cart';
        });
	</script>
</body>
</html>