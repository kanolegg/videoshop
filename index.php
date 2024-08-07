<?php

require __DIR__.'/classes/db.class.php';
require __DIR__.'/classes/main.class.php';

$core = new core();

$catalog = $core->getCatalog();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $authorized = (!$core->checkAuth($_COOKIE['id'], $_COOKIE['hash'])) ? false : true;
}

$cart = (array) json_decode($_COOKIE['cart']);
$features = [
    [
        'icon' => 'house-circle-check',
        'title' => 'Умный дом под ключ'
    ],
    [
        'icon' => 'lightbulb',
        'title' => 'Управление светом'
    ],
    [
        'icon' => 'sun-cloud',
        'title' => 'Управление климатом'
    ],
    [
        'icon' => 'video',
        'title' => 'Видеонаблюдение'
    ],
    [
        'icon' => 'film',
        'title' => 'Кинотеатр'
    ],
    [
        'icon' => 'fan',
        'title' => 'Вентиляция'
    ]
];

$management = [
    [
        'icon' => 'tablet',
        'title' => 'Смартфон/планшет'
    ],
    [
        'icon' => 'robot-astromech',
        'title' => 'ИИ'
    ],
    [
        'icon' => 'cloud-check',
        'title' => 'Облачные технологии'
    ],
    [
        'icon' => 'microphone',
        'title' => 'Голосовое управление'
    ]
];

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
	<title>Security</title>
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
                    <div class="rounded p-5 text-white" style="background: url(/assets/img/banner-bg.jpg);background-position: center; background-size: cover;">
                        <div class="row align-items-center">
                            <div class="col-md-10 col-lg-8">
                                <h3 class="fw-bold">Интернет-магазин интеллектуальных систем безопасности</h3>
                                <p>Умный дом призван делать простым всё, что ранее казалось сложным. Вы готовы впустить смарт-решения в повседневность?</p>
                                <div class="d-flex">
                                    <a href="/#smart" class="btn btn-secondary me-2">Заказать</a>
                                    <a href="/smart" class="btn btn-secondary">Подробнее</a>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-4 d-none d-lg-flex">
                                <img class="img-fluid" src="/assets/img/banner-image.png">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-3">
                <div class="container">
                    <div class="row g-3 mb-3">
                        <?php $n = 0; foreach($catalog as $category):?>
                            <?php if ($n === 6) break;?>
                            <div class="col-md-6 col-lg-4">
                                <a href="/category/<?=$category['url']?>">
                                    <div class="bg-white border rounded-1 shadow-sm p-3 category-item">
                                        <div class="d-flex justify-content-between align h-100">
                                            <div class="d-flex flex-column justify-content-between">
                                                <div class="fw-bold mb-3 text-dark"><?=$category['title']?></div>
                                                <div class="text-muted d-flex align-items-center">Подробнее <i class="fa-solid fa-angle-right ms-1"></i></div>
                                            </div>
                                            <div>
                                                <img src="/uploads/<?=$category['image']?>" class="img-fluid" alt="<?=$category['title']?>">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php $n = $n + 1;?>
                        <?php endforeach;?>
                    </div>
                    <div class="text-center">
                        <div class="d-flex justify-content-center">
                            <a href="/catalog" class="btn btn-primary px-3 border-secondary d-flex align-items-center" style="column-gap: 8px">
                                Каталог
                                <i class="fa-regular fa-chevrons-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-3" id="smart">
                <div class="container">
                    <h2>Калькулятор умного дома</h2>
                    <form class="rounded bg-white border p-4" id="smart-home">
                        <!-- Кнопки навигации (скрыты) -->
                        <ul class="nav nav-tabs d-none" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="place-tab" data-bs-toggle="tab" data-bs-target="#place" type="button" role="tab" aria-controls="place" aria-selected="true">Где установить</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="area-tab" data-bs-toggle="tab" data-bs-target="#area" type="button" role="tab" aria-controls="area" aria-selected="false">Площадь</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab" aria-controls="features" aria-selected="false">Возможности</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button" role="tab" aria-controls="management" aria-selected="false">Управление</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="false">Данные</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="result-tab" data-bs-toggle="tab" data-bs-target="#result" type="button" role="tab" aria-controls="result" aria-selected="false">Результат</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Расположение -->
                            <div class="tab-pane fade show active" id="place" role="tabpanel" aria-labelledby="place-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Где хотелось бы установить умный дом?</h4>
                                           <div class="col-md-6 col-xl-4">
                                                <div class="rounded border d-flex flex-column">
                                                    <label class="form-check-label" for="place-1">
                                                        <div class="p-3">
                                                            <div class="d-flex flex-row">
                                                                <input type="radio" class="form-check-input" name="options-place" id="place-1" autocomplete="off" value="1" />
                                                                <div class="d-flex justify-content-center ms-1">
                                                                    <div class="text-center">
                                                                        <i class="fa-light fa-city fa-3x"></i>
                                                                        <div>Квартира</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-xl-4">
                                                <div class="rounded border d-flex flex-column">
                                                    <label class="form-check-label" for="place-2">
                                                        <div class="p-3">
                                                            <div class="d-flex flex-row">
                                                                <input type="radio" class="form-check-input" name="options-place" id="place-2" autocomplete="off" value="2" />
                                                                <div class="d-flex justify-content-center ms-1">
                                                                    <div class="text-center">
                                                                        <i class="fa-light fa-house fa-3x"></i>
                                                                        <div>Коттедж</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center">
                                                <button type="button" class="btn btn-success btn-rounded" id="place-click" disabled>
                                                    Далее
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-2 col-md-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-10 col-md-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">В зависимости от типа объекта, свет и климат-контроль разный</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Квадратура -->
                            <div class="tab-pane fade" id="area" role="tabpanel" aria-labelledby="area-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Укажите площадь помещения, м<sup>2</sup></h4>
                                            <div class="col-md-12">
                                                <input class="form-control onlyDigits" type="text" name="area" id="area-input" autocomplete="off" placeholder="Введите квадратуру">
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center" style="column-gap: 8px">
                                                <button type="button" class="btn btn-info btn-rounded" onclick="activeTab('place')">
                                                    <i class="fa-light fa-arrow-left"></i>
                                                    Назад
                                                </button>
                                                <button type="button" class="btn btn-success btn-rounded" id="area-click" disabled>
                                                    Далее
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">Мы рассчитаем, сколько будет групп света и климатических зон</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Возможности -->
                            <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Выберите, какие возможности Умного дома интересуют?</h4>
                                            <div class="col-md-12">
                                                <div class="row g-3 overflow-y-auto" style="height: 24rem;">
                                                    <?php for ($i = 1; $i <= count($features); $i = $i + 1):?>
                                                        <div class="col-md-6 col-xl-4">
                                                            <div class="rounded border d-flex flex-column overflow-hidden">
                                                                <label class="form-check-label" for="<?=$core->translit($features[$i-1]['title'])?>">
                                                                    <div class="p-3">
                                                                        <input class="form-check-input features" type="checkbox" value="<?=$i?>" id="<?=$core->translit($features[$i-1]['title'])?>">
                                                                        <div class="text-center">
                                                                            <i class="fa-light fa-<?=$features[$i-1]['icon']?> fa-3x"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="bg-light p-3">
                                                                        <div class="row-cut-1" title="<?=$features[$i-1]['title']?>"><?=$features[$i-1]['title']?></div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endfor;?>
                                                    
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center" style="column-gap: 8px">
                                                <button type="button" class="btn btn-info btn-rounded" onclick="activeTab('area')">
                                                    <i class="fa-light fa-arrow-left"></i>
                                                    Назад
                                                </button>
                                                <button type="button" class="btn btn-success btn-rounded" id="features-click" disabled>
                                                    Далее
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">У нас более 72 функций умного дома, здесь приведены основные</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Управление -->
                            <div class="tab-pane fade" id="management" role="tabpanel" aria-labelledby="management-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Выберите способ управления</h4>
                                            <?php for ($i = 1; $i <= count($management); $i = $i + 1):?>
                                                <div class="col-xl-4">
                                                    <div class="rounded border d-flex flex-column">
                                                        <label class="form-check-label" for="management-<?=$i?>">
                                                            <div class="p-3">
                                                                <div class="d-flex flex-row">
                                                                    <input type="radio" class="form-check-input" name="options-management" id="management-<?=$i?>" autocomplete="off" value="<?=$i?>" />
                                                                    <div class="d-flex justify-content-center">
                                                                        <div class="ms-1">
                                                                            <i class="fa-light fa-<?=$management[$i-1]['icon']?> fa-3x"></i>
                                                                            <div class="row-cut-1" title="<?=$management[$i-1]['title']?>"><?=$management[$i-1]['title']?></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endfor;?>
                                            <div class="d-flex justify-content-end align-items-center" style="column-gap: 8px">
                                                <button type="button" class="btn btn-info btn-rounded" onclick="activeTab('features')">
                                                    <i class="fa-light fa-arrow-left"></i>
                                                    Назад
                                                </button>
                                                <button type="button" class="btn btn-success btn-rounded" id="management-click" disabled>
                                                    Далее
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">Может быть, хлопком?</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Управление -->
                            <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Оставьте свои данные, и мы свяжемся с вами</h4>
                                            <div class="mb-3">
                                                <label class="form-label" for="name">Ваше имя</label>
                                                <input type="text" class="form-control personal onlyLetters" id="name" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="phone">Номер телефона</label>
                                                <input type="text" class="form-control personal" id="phone" autocomplete="off">
                                            </div>
                                            <div class="d-flex justify-content-end align-items-center" style="column-gap: 8px">
                                                <button type="button" class="btn btn-info btn-rounded" onclick="activeTab('management')">
                                                    <i class="fa-light fa-arrow-left"></i>
                                                    Назад
                                                </button>
                                                <button type="button" class="btn btn-success btn-rounded" id="personal-click" disabled>
                                                    Отправить
                                                    <i class="fa-light fa-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">Как с вами связаться?</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Результат -->
                            <div class="tab-pane fade" id="result" role="tabpanel" aria-labelledby="result-tab">
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-8 row" style="grid-row-gap: 8px;">
                                            <h4>Заявка зарегистрирована</h4>
                                            <p>Обработка заявки обычно занимает от 1-2 часа, наши менеджеры свяжутся с вами, чтобы назначить визит.</p>
                                            <div class="d-flex justify-content-end align-items-center" style="column-gap: 8px">
                                                <button type="button" class="btn btn-info btn-rounded" onclick="activeTab('place')">
                                                    <i class="fa-light fa-arrow-left"></i>
                                                    Вернуться
                                                </button>
                                            </div>
                                        </div>
                                        <div class="vr vr-blurry d-none d-md-block p-0 bg-dark"></div>
                                        <div class="col-md-4">
                                            <div class="row mb-3">
                                                <div class="col-4">
                                                    <img src="/assets/img/manager.png" class="img-fluid ratio ratio-1x1 rounded-circle">
                                                </div>
                                                <div class="col-8">
                                                    <div class="d-flex flex-column">
                                                        <div class="fw-bold">Алексей</div>
                                                        <div class="text-muted">Менеджер</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-light rounded p-3">Скоро вам позвоними, будьте на связи!</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
            <section class="py-3">
                <div class="container">
                    <h2>Популярные товары</h2>
                    <div class="row" id="popular"></div>
                    <div class="text-center">
                        <button class="btn btn-secondary" id="showMore">Показать ещё</button>
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
    <script src="/assets/js/jquery.inputmask.min.js"></script>
    <script src="/assets/js/jquery.loadTemplate.min.js"></script>
    <script type="text/html" id="template">
        <div class="col-md-6 col-lg-3 mb-3">
            <div class="border rounded-1 shadow-sm product-card bg-white p-3">
                <div class="mb-0 d-flex flex-column align-items-center justify-content-between h-100">
                    <div class="text-center position-relative w-100">
                        <a data-href="link">
                            <img data-src="image" class="img-fluid">
                        </a>
                    </div>
                    <div class="w-100">
                        <a class="mb-3" data-href="link">
                            <div class="text-muted row-cut-3" data-content="name"></div>
                        </a>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="fw-bold" data-content="price">₽</h4>
                            <button class="btn btn-primary text-white addToCart" data-bs-toggle="tooltip" data-bs-placement="top" title="В корзину" data-template-bind='[{"attribute": "data-id", "value": "id"}]'>
                                <i class="fa-regular fa-cart-shopping"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
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