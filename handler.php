<?php

include 'classes/main.class.php';
include 'classes/db.class.php';

$core = new core();

$result = array(
	array(
		'name' => 'Датчик движения и разбития Ajax CombiProtect',
		'price' => 4800,
		'image' => '/uploads/adrvwfw.png'
	),
	array(
		'name' => 'Карта памяти micro-SD Class 10 64Гб',
		'price' => 1000,
		'image' => '/uploads/eflrmsnf.png'
	),
	array(
		'name' => 'Антивандальная IP-камера RVi-IPC32',
		'price' => 22240,
		'image' => '/uploads/sdcerbwf.png'
	),
	array(
		'name' => 'Вызывная панель Tantos iPanel 2',
		'price' => 7420,
		'image' => '/uploads/fgthoekf.png'
	),
	array(
		'name' => 'Датчик движения и разбития Ajax CombiProtect',
		'price' => 4800,
		'image' => '/uploads/adrvwfw.png'
	),
	array(
		'name' => 'Карта памяти micro-SD Class 10 64Гб',
		'price' => 1000,
		'image' => '/uploads/eflrmsnf.png'
	),
	array(
		'name' => 'Антивандальная IP-камера RVi-IPC32',
		'price' => 22240,
		'image' => '/uploads/sdcerbwf.png'
	),
	array(
		'name' => 'Вызывная панель Tantos iPanel 2',
		'price' => 7420,
		'image' => '/uploads/fgthoekf.png'
	)
);

if (isset($_FILES) && isset($_FILES['photo'])) {
  $image = $_FILES['photo'];

  $imageFormat = pathinfo($image['name'], PATHINFO_EXTENSION);
 
  // Генерируем новое имя для изображения
  $name = hash('crc32',time());
  $photoName = $name . '.' . $imageFormat;
  $imageFullName = './uploads/' . $photoName;
 
  // Сохраняем тип изображения в переменную
  $imageType = $image['type'];
 
  // Сверяем доступные форматы изображений, если изображение соответствует,
  // копируем изображение в папку images
  if ($imageType == 'image/jpeg' || $imageType == 'image/png') {
    if (move_uploaded_file($image['tmp_name'],$imageFullName)) {
      echo json_encode(
			[
				'ext' => $imageFormat,
				'name' => $name
			],
			JSON_UNESCAPED_UNICODE
		);
    } else {
      echo 'error';
    }
  }

  exit();
}

if (!isset($_POST['method']))
	exit(json_encode(
		array(
			'ok' => false,
			'message' => 'Method not passed'
		),
		JSON_UNESCAPED_UNICODE
	));
else
	$method = $_POST['method'];

if (isset($_POST['params'])) $params = $_POST['params'];

if ($method === 'getProducts') {
	if (!isset($params['limit']))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'Limit not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	$limit = $params['limit'];

	$output = []; $n = 0;

	$products = $core->getProducts();

	foreach ($products as $item) {
		if ($n == $params['limit']) break;
		$output[] = $item;
		$n = $n + 1;
	}

	exit(json_encode($output, JSON_UNESCAPED_UNICODE));
}

else if ($method === 'productLike') {
	$id = $params['product_id'];

	if (!isset($id))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'ID not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	$output = []; $n = 0;

	if (!isset($_COOKIE['favourites']))
		$favourites = [];
	else
		$favourites = (array)json_decode($_COOKIE['favourites']);

	$favourites[] = $id;

	$favourites = json_encode($favourites, JSON_UNESCAPED_UNICODE);

	setcookie('favourites', $favourites, time() + 60*60*24*30, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Успешно'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'productDislike') {
	$id = $params['product_id'];

	if (!isset($id))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'ID not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	$output = []; $n = 0;

	if (!isset($_COOKIE['favourites']))
		$favourites = [];
	else
		$favourites = (array)json_decode($_COOKIE['favourites']);

	if (($key = array_search($id, $favourites)) !== false) {
	    unset($favourites[$key]);
	}

	$favourites = json_encode($favourites, JSON_UNESCAPED_UNICODE);

	setcookie('favourites', $favourites, time() + 60*60*24*30, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Успешно'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'cartSend') {
	if (!isset($params))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'ID not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	$output = []; $n = 0;

	setcookie('favourites', $favourites, time() + 60*60*24*30, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Успешно'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
} else if ($method === 'addToCart') {
	$product_id = $params['product_id'];
	$amount = $params['amount'];

	if (!isset($product_id))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'ID not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	if (!isset($_COOKIE['cart']))
		$cart = [];
	else
		$cart = (array)json_decode($_COOKIE['cart']);

	$cart[$product_id] = $amount;

	$cart = json_encode($cart, JSON_UNESCAPED_UNICODE);

	setcookie('cart', $cart, time() + 60*60*24*30, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Успешно'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'removeFromCart') {
	$product_id = $params['product_id'];

	if (!isset($product_id))
		exit(json_encode(
			array(
				'ok' => false,
				'message' => 'ID not passed'
			),
			JSON_UNESCAPED_UNICODE
		));

	$cart = (array)json_decode($_COOKIE['cart']);

	unset($cart[$product_id]);

	$cart = json_encode($cart, JSON_UNESCAPED_UNICODE);

	setcookie('cart', $cart, time() + 60*60*24*30, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Успешно'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'order') {
	$user_id = $_COOKIE['id'];

	if (!isset($params['products'])) {
		exit(
			json_encode(
				[
					'error' => true,
					'message' => 'Не передан список товаров'
				],
				JSON_UNESCAPED_UNICODE
			)
		);
	}

	$order_preview = $core->getProduct(array_keys($params['products'])[0])['image'];

	$products = ''; $s = '';
    foreach ($params['products'] as $title => $c) {
        $products .= $s.$title;
        $s = ', ';
    }

    $products = $core->getProductsByIds($products);

    $price = 0;

    foreach ($products as $product) {
    	$price += $product['price'] * $params['products'][$product['id']];
    }

	$id = $core->orderCreate($user_id, json_encode($params['products']), $order_preview, $price);
	$content = json_encode(
		[
			'order_id' => $id
		],
		JSON_UNESCAPED_UNICODE
	);
	$core->notificationCreate($user_id, $content, 'ORDER_CREATION');

	setcookie('cart', null, -1, '/');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Заказ оформлен'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'cancelOrder') {
	$core->cancelOrder($params['id']);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Заказ отменён'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'passwordUpdate') {
	$userData = $core->getUser($params['user_id']);

	if (!$core->isValidPassword($params['password_old'], $userData['password'])) {
		exit(
			json_encode(
				[
					'error' => true,
					'message' => 'Неправильный старый пароль'
				],
				JSON_UNESCAPED_UNICODE
			)
		);
	}

	$core->accountUpdate($params['user_id'], ['password' => $core->hash($params['password_new'])]);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Пароль обновлён'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'accountUpdate') {
	$core->accountUpdate($_POST['user_id'], $params);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Данные обновлены'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'categoryDelete') {
	$core->categoryDelete($params['id']);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Категория удалена'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'getCategoryById') {
	$result = $core->getCategoryByUrl($params['url']);

	exit(
		json_encode(
			[
				'error' => false,
				'category' => $result
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'createProduct') {
	$params['date_added'] = $_SERVER['REQUEST_TIME'];

	$params['related'] = json_encode(explode(',', str_replace(' ', '', $params['related'])), JSON_UNESCAPED_UNICODE);

	$core->createProduct($params);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Товар добавлен'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'updateProduct') {
	$product_id = $_POST['product_id'];
	$params['related'] = json_encode(explode(',', str_replace(' ', '', $params['related'])), JSON_UNESCAPED_UNICODE);
	
	$core->updateProduct($product_id, $params);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Товар обновлён'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'createProduct') {
	$params['date_added'] = $_SERVER['REQUEST_TIME'];

	$core->createProduct($params);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Товар добавлен'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'productDelete') {
	$id = $params['id'];

	$core->productDelete($id);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Товар удалён'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'feedbackCreate') {
	$name = $params['name'];
	$email = $params['email'];
	$message = $params['message'];

	$core->feedbackCreate($name, $email, $message);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Завяка зарегистрирована'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'orderPay') {
	$order_id = $params['order_id'];
	$user_id = $params['user_id'];

	$core->orderStatusUpdate($order_id, 1);
	$core->notificationCreate($user_id, json_encode(['order_id'=>$order_id]), 'ORDER_PAYMENT');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Заказ оплачен'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'orderStatusUpdate') {
	$order_id = $params['order_id'];
	$user_id = $params['user_id'];
	$status = $params['status'];

	$core->orderStatusUpdate($order_id, $status);
	$core->notificationCreate($user_id, json_encode(['order_id'=>$order_id, 'order_status']), 'ORDER_CONFIRMATION');

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Статус заказа обновлён'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'smartHomeOrder') {
	$core->smartHomeOrder($params);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Завяка отправлена'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}

else if ($method === 'smartHomeClose') {
	$core->smartHomeClose($params['id']);

	exit(
		json_encode(
			[
				'error' => false,
				'message' => 'Завяка закрыта'
			],
			JSON_UNESCAPED_UNICODE
		)
	);
}