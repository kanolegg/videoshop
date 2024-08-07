<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

$cart = (array) json_decode($_COOKIE['cart']);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = $core->getProduct(mb_ereg_replace('[^0-9]', '', $id));
    if ($product) {
        $category = $core->getCategoryByUrl($product['category']);

        $specs = (array) json_decode($product['specs']);

        $params = [];

        $related = json_decode($product['related']);
        if ($related) {
            $related_str = ''; $s = '';
            foreach ($related as $r) {
                $related_str .= $s . '\''.$r.'\'';
                $s = ',';
            }
            $related = $core->getProductsByIds($related_str);
        }
    }
} else {
    header('Location: /catalog');
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
	<title><?=$product['name']?> / Security</title>
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
                <span class="text-muted row-cut-1">
                    <a href="/catalog">Каталог</a> &#187;
                    <a href="/category/<?=$section['url']?>"><?=$section['name']?></a> &#187;
                    <span title="<?=$product['title']?>"><?=$product['title']?></span>
                </span>
    			<div class="my-2 px-3 rounded border bg-white">
    				<div class="my-2 row">
                        <div class="col-md-6 col-lg-5 mb-3">
                            <div class="p-2">
                                <div class="image-wrapper">
                                    <img src="<?php if (isset($product['image'])):?>/uploads/<?=$product['image']?><?php else:?>/img/noimg.png<?php endif;?>" class="img-fluid">
                                </div>
                                <?php if ($photos):?>
                                    <hr>
                                    <div class="product-images">
                                        <?php foreach($photos as $p):?>
                                            <div class="product-img-small">
                                                <img src="<?=$p['path']?>" class="img-fluid product-img-preview">
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-7">
                            <div class="p-2">
                                <h4 class="fw-bold row-cut-3 product-title" title="<?=htmlspecialchars($product['name'])?>"><?=$product['name']?></h4>
                                <div class="mb-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto mb-1">
                                            <div class="price text-danger fw-bold" style="font-size: 22px; white-space: nowrap"><?=number_format($product['price'], 2, ',', ' ')?>₽</div>
                                        </div>
                                        <div class="col-auto mb-1">
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-primary px-2" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input min="1" max="<?=$product['amount']?>" value="1" type="number" class="form-control form-control-sm amount mx-2" id="amount">
                                                <button type="button" class="btn btn-primary px-2" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-auto mb-1">
                                            <button class="btn btn-success addToCart border-0" data-product-id="<?=$product['id']?>">
                                                <i class="fa-regular fa-cart-shopping" style="font-size: 22px;"></i>
                                                <span class="fw-bold">Купить</span>
                                            </button>
                                        </div>
                                        <div class="col-auto mb-1">
                                            <?php if ($product['amount'] > 0):?>
                                                <div class="p-2 rounded d-flex align-items-center" style="background-color: #f4f9e7; color: #89b32b;">
                                                    <i class="fa-regular fa-circle-check" style="font-size: 22px"></i>
                                                    <span class="ms-2 fw-semibold" style="white-space: nowrap;color: #89b32b;">В наличии: <?=$product['amount']?></span>
                                                </div>
                                            <?php else:?>
                                                <div class="p-2 rounded d-flex align-items-center" style="background-color: #f9e1e5; color: #af233a;">
                                                    <i class="fa-regular fa-circle-xmark" style="font-size: 22px"></i>
                                                    <span class="ms-2 text-danger fw-semibold" style="white-space: nowrap;">Нет в наличии</span>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <div class="shadow-sm bg-light p-1 d-flex flex-wrap mb-3">
                                    <div class="bg-white d-flex flex-wrap">
                                        <div class="d-flex p-1">
                                            <div class="fw-bold">Артикул:</div>
                                            <div class="fw-bold ms-2">
                                                <span class="text-primary" data-clipboard-text="<?=$product['article']?>" style="white-space: nowrap;"><?=$product['article']?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex p-1">
                                            <div class="fw-bold">Модель:</div>
                                            <div class="fw-bold ms-2">
                                                <span class="text-primary copy"><?=$product['model']?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex p-1">
                                        <div class="fw-bold">Производитель:</div>
                                        <div class="fw-bold ms-2">
                                            <span class="text-primary"><?=$product['manufacturer']?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($specs)):?>
                            <h4>Характеристики</h4>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 bg-white">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">Параметр</th>
                                            <th scope="col">Значение</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($specs as $title => $p):?>
                                            <tr>
                                                <td><?=$title?></td>
                                                <td><?=$p?></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif;?>
                    </div>
                    <?php if ($product['related']):?>
                        <div class="px-2 mb-2">
                            <h4>Сопутствующие товары</h4>
                            <div class="row">
                                <?php foreach($related as $r):?>
                                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                                        <div class="shadow-sm border rounded height-100">
                                            <div class="p-3">
                                                <div class="text-center">
                                                    <a href="/product/<?=$r['id']?>">
                                                        <img src="<?php if (isset($r['image'])):?>/uploads/<?=$r['image']?><?php else:?>/img/noimg.png<?php endif;?>" class="img-fluid" style="max-height: 14rem;">
                                                    </a>
                                                </div>
                                                <a href="/product/<?=$r['id']?>" class="fw-bold row-cut-3" title="<?=htmlspecialchars($r['name'])?>"><?=$r['name']?></a>
                                                <div class="mb-2">Код: <?=$r['article']?></div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="fw-bold" style="font-size: 18px"><?=number_format($r['price'], 2, ',', ' ')?>₽</div>
                                                    <div class="bottom text-end item-buttons">
                                                        <?php if (!in_array($r['id'], $cart)):?>
                                                            <button class="ms-1 btn btn-outline-secondary addToCart related" data-product-id="<?=$r['id']?>">
                                                                <i class="fa-regular fa-cart-shopping"></i>
                                                            </button>
                                                        <?php else:?>
                                                            <a href="/cart" class="ms-1 btn btn-outline-secondary bg-warning text-white border-0">
                                                                <i class="fa-regular fa-cart-shopping"></i>
                                                            </a>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endif;?>
    			</div>
    		</div>
    	</section>
    </main>
    <div class="modal fade" id="cartModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Корзина</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Товар добавлен в корзину</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Продолжить</button>
                    <a href="/cart" class="btn btn-success">В корзину</a>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<script>
		let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});

        $(".product-img-preview").on("click", function() {
            let src = $(this).attr("src");
            $(".image-wrapper img").attr("src", src);
        });

        $(".addToCart").on("click", function() {
            let id = $(this).data("product-id");

            if ($(this).hasClass("related")) {
                amount = 1;
            } else {
                amount = $("#amount").val();
            }

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'addToCart',
                    'params': {
                        'product_id': id,
                        'amount': amount
                    }
                },
                success: function(response) {
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
	</script>
</body>
</html>