<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

$catalog = $core->getCatalog();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $authorized = (!$core->checkAuth($_COOKIE['id'], $_COOKIE['hash'])) ? false : true;
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
	<title>Умный дом / Security</title>
</head>
<body>
    <div class="d-flex flex-column h-100">
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
                    <div class="rounded border bg-white p-3">
                        <h2>Умный дом «под ключ»</h2>
                        <p>Если вы не профессиональный инсталятор и не обладаете навыками монтажа систем Умный Дом, вы можете заказать Умный Дом «под ключ» в нашей компании. Мы профессионально подберем оборудование и выполним монтаж системы «под ключ». Установка оборудования займёт всего 1-2 дня.</p>
                        <p>Смотрите, как это работает:</p>
                        <iframe loading="lazy" width="560" height="315" src="https://youtube.com/embed/pqPtg4pcnBE" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen=""></iframe>
                        <p> Заказывая «Умный Дом» по ключ у нас, вы получаете следующие преимущества:</p>
                        <ul>
                            <li>настройка готовых сценариев для управления «Умным домом»;</li>
                            <li> скидка 10% на все оборудование для «Умного Дома»;</li>
                            <li>установка оборудования для мобильного управления в подарок;</li>
                            <li> расширенная гарантия на 3 года на все оборудование; </li>
                            <li>скидка 15% на все общестроительные электромонтажные работы.</li>
                        </ul>
                        <p>Для заказа умного дома «под ключ» и расчёта стоимости Вы можете заполнить специальный калькулятор умного дома.</p>
                        <a href="/#smart" class="btn btn-warning">Заполнить</a>
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
    </div>
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
	<script>
		let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});

        $(".onlyLetters").on("input", function() {
            let node = $(this);
            node.val(node.val().replace(/[^а-яё ]/ig,'') );
        });

        $(".onlyDigits").on("input", function() {
            let node = $(this);
            node.val(node.val().replace(/[^0-9]/ig,''));
        });

        limit = 0;
        let params = {};

        $(document).ready(function() {
            getProducts();
            // activeTab('personal');
            $('#phone').inputmask("+7 (999) 999-9999");
        });

        function activeTab(tab) {
            $('[data-bs-target="#'+tab+'"]').tab('show');
            $('html, body').animate({
                scrollTop: $("#"+tab).offset().top -100
            });
        };

        $('input[name="options-place"]').on("change", function() {
            $("#place-click").removeAttr("disabled");
        });

        $("#place-click").on("click", function() {
            params.place = parseInt($('input[name="options-place"]:checked').val());
            activeTab("area");
        });

        $("#area-input").on("input", function() {
            if ($(this).val().trim() !== '')
                $("#area-click").removeAttr("disabled");
        });

        $("#area-click").on("click", function() {
            params.area = $("#area-input").val();
            activeTab('features');
        });

        $(".features").on("click", function() {
            if ($(".features:checked").length !== 0) {
                $("#features-click").removeAttr("disabled");
            } else {
                $("#features-click").attr("disabled", true);
            }
        });

        $("#features-click").on("click", function() {
            let temp = [];
            $('input:checkbox:checked').each(function(){
                temp.push(parseInt($(this).val()));
            });
            params.features = JSON.stringify(temp);
            activeTab("management");
        });

        $('input[name="options-management"]').on("change", function() {
            $("#management-click").removeAttr("disabled");
        });

        $("#management-click").on("click", function() {
            params.management = parseInt($('input[name="options-management"]:checked').val());
            activeTab("personal");
        });

        $(".personal").on("input paste", function() {
            if ($("#name").val().trim() != "" && $("#phone").val().trim() != "")
                $("#personal-click").removeAttr("disabled");
            else
                $("#personal-click").attr("disabled", true);
        });

        $("#personal-click").on("click", function() {
            let name = $("#name").val().trim();
            let phone = $("#phone").val().trim();

            params.name = name;
            params.phone = phone;

            $(':input','#smart-home')
              .not(':button, :submit, :reset, :hidden')
              .val('')
              .prop('checked', false)
              .prop('selected', false);

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'smartHomeOrder',
                    'params': params
                },
                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (!response.error) {
                        activeTab("result");
                    } else {
                        console.log(response.error);
                    }

                }
            });
        });

        $("#showMore").on("click", function() {
            getProducts();
        });

        function getProducts() {
            limit += 4;
            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'getProducts',
                    'params': {
                        'limit': limit
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    for (let i = 0; i < response.length; i = i + 1) {
                        response[i].price = response[i].price+"₽";
                        response[i].image = "/uploads/"+response[i].image;
                        response[i].link = "/product/"+response[i].id;
                    }
                    $("#popular").loadTemplate($("#template"), response);

                }
            });
        }

        $(".search").click(function(e) {
            if ($(this).closest(".input-group").find("input").val() == "") {
                e.preventDefault();
            }
        });

        $("#popular").on("click", ".addToCart", function() {
            let id = $(this).data("id");

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'addToCart',
                    'params': {
                        'product_id': id,
                        'amount': 1
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
	</script>
</body>
</html>