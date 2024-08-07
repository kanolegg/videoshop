<?php

$config = array(
	'db' => array(
		'host' 		=> '127.0.0.1',
		'user' 		=> 'root',
		'password' 	=> 'root',
		'name' 		=> 'video'
	),

	'menu' => array(
		[
			'title' => 'Главная',
			'href' => '/admin'
		],
		[
			'title' => 'Товары',
			'href' => '/admin/products'
		],
		[
			'title' => 'Аккаунт',
			'href' => '/account/profile'
		],
		[
			'title' => 'Заказы',
			'href' => '/admin/orders'
		],
		[
			'title' => 'Умный дом',
			'href' => '/admin/smart'
		],
		[
			'title' => 'Обратная связь',
			'href' => '/admin/feedback'
		],
		[
			'title' => 'Выйти',
			'href' => '/account/login?act=logout'
		]
	)
);