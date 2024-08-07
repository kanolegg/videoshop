<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $authorized = (!$core->checkAuth($_COOKIE['id'], $_COOKIE['hash'])) ? false : true;
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
    <title>Доставка</title>
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
                <div class="mb-3">
                    <h3 class="fw-normal mb-0 text-black">Доставка</h3>
                </div>
                <div class="rounded p-4 mb-3 shadow-sm" style="background: linear-gradient(180deg, #f5f5f6 25.34%, #ffd1d9 100%);">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-3">Экспресс-доставка от 2 часов</h4>
                            <small>* При заказе до 20:00</small>
                            <p class="fw-semibold">Быстрая доставка — оперативное получение нужной техники в особых случаях!</p>
                            <div class="w-75 position-relative" style="margin: 0 auto; left: -16px;">
                                <img src="/assets/img/car.svg" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="fw-bold mb-3">Получи заказ максимально быстро:</h4>
                            <div class="d-flex flex-row align-items-center mb-3">
                                <div class="d_number">1</div>
                                <div class="fw-semibold">Добавь товары в корзину, оформи и оплати заказ</div>
                            </div>
                            <div class="d-flex flex-row align-items-center mb-3">
                                <div class="d_number">2</div>
                                <div class="fw-semibold">Наш менеджер свяжется с тобой в течение 15 минут для подтверждения</div>
                            </div>
                            <div class="d-flex flex-row align-items-center mb-3">
                                <div class="d_number">3</div>
                                <div class="fw-semibold">Ожидай доставку в течение 2 – 3 часов!</div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        <section class="py-3">
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-bordered bg-white">
                        <thead>
                            <tr>
                                <th scope="col">География</th>
                                <th scope="col">Интервал доставки</th>
                                <th scope="col">Доставка (₽)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td rowspan="4" style="vertical-align: middle;">Набережные Челны</td>
                                <td>09:00-22:00</td>
                                <td>899</td>
                            </tr>
                            <tr>
                                <td>15:00-22:00</td>
                                <td>899</td>
                            </tr>
                            <tr>
                                <td>10:00-14:00</td>
                                <td>999</td>
                            </tr>
                            <tr>
                                <td>13:00-17:00</td>
                                <td>999</td>
                            </tr>
                            <tr>
                                <td>Казань</td>
                                <td>09:00-22:00</td>
                                <td>1699</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section class="pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="bg-white rounded border p-3">
                            <h4>Задайте нам вопрос!</h4>
                            <div class="alert alert-success alert-dismissible fade show" id="feedback-alert" role="alert" style="display: none;">
                                Заявка отправлена. Мы пришлём вам ответ на Email!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <form id="feedback-form">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Ваше имя</label>
                                    <input type="text" class="form-control" autocomplete="off" id="name" placeholder="Иван">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="email@example.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="message">Текст</label>
                                    <textarea class="form-control" id="message" placeholder="Сообщение"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" id="feedback">Отправить</button>
                                </div>
                            </form>
                        </div>
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

        $("#feedback").on("click", function() {
            let btn = $(this);
            btn.attr("disabled", true);

            if ($("#name").val().trim() === "")
                return $("#name").focus();

            if ($("#email").val().trim() === "")
                return $("#email").focus();

            if ($("#message").val().trim() === "")
                return $("#message").focus();
            
            let name = $("#name").val().trim();
            let email = $("#email").val().trim();
            let message = $("#message").val().trim();

            $.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'feedbackCreate',
                    'params': {
                        'name': name,
                        'email': email,
                        'message': message
                    }
                },
                success: function(response) {
                    console.log(response)
                    response = JSON.parse(response);
                    if (!response.error) {
                        btn.removeAttr("disabled");
                        $(".alert-field").val("");
                        $("#feedback-alert").show();
                        $("#feedback-form :input").each(function(i, item){
                            $(item).val("");
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>