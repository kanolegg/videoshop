<?php

include __DIR__.'/../classes/main.class.php';
include __DIR__.'/../classes/db.class.php';
$core = new core();

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    if (!$core->checkAuth($_COOKIE['id'], $_COOKIE['hash']))
        header('Location: /account/login');
} else {
    header('Location: /account/login');
}

$menu = $core->menu();

$userData = $core->getUser($_COOKIE['id']);
if (!$userData['rank'])
	return header('Location: /');

$users = $core->getUsers();
$orders = $core->getOrders(false, true);
$products = $core->getProducts();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Главная / Управление</title>
</head>
<body>
	<div id="wrapper">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <h2 class="pt-2 text-white">Управление</h2>
                </li>
                <?php foreach($menu as $m):?>
                	<li>
                		<?php if(isset($m['children'])):?>
                			<a href="#<?=$m['href']?>" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" aria-controls="<?=$m['href']?>"><?=$m['title']?></a>
                			<ul class="collapse list-unstyled" id="<?=$m['href']?>">
                				<?php foreach ($m['children'] as $sub):?>
                					<li>
			                    		<a href="<?=$sub['href']?>"><?=$sub['title']?></a>
			                    	</li>
                				<?php endforeach;?>
		                    </ul>
                		<?php else:?>
                			<li class="active">
			                    <a href="<?=$m['href']?>"><?=$m['title']?></a>
                			</li>
                		<?php endif;?>
                		</a>
                	</li>
                <?php endforeach;?>
            </ul>
        </div>
        <main>
        	<div class="navbar p-4 mb-2 d-flex justify-content-between shadow-sm align-items-center">
        		<button type="button" id="sidebarCollapse" class="btn btn-info">
        			<i class="fas fa-align-left"></i>
        		</button>
        		<div class="navbar-brand fw-bold fs-4">Security</div>
        	</div>
        	<div class="container">
        		<h2>Главная</h2>
        		<div class="row mb-3">
        			<div class="col-md-4">
        				<div class="rounded bg-white border p-3">
        					<div class="d-flex flex-row align-items-center">
        						<div class="rounded p-3" style="background: #ffeabc;">
        							<h4 class="mb-0" style="color: #958560;">
        								<i class="fa-solid fa-users"></i>
        							</h4>
        						</div>
        						<div class="ms-3">
        							<h4 class="mb-0"><?php if ($users):?><?=count($users)?><?php else:?>0<?php endif;?></h4>
        							<small>Пользователи</small>
        						</div>
        					</div>
        				</div>
        			</div>
        			<div class="col-md-4">
        				<div class="rounded bg-white border p-3">
        					<a href="/admin/orders" class="d-flex flex-row align-items-center text-dark">
        						<div class="rounded p-3" style="background: #d8befe;">
        							<h4 class="mb-0" style="color: #8369a8;">
        								<i class="fa-solid fa-cart-shopping"></i>
        							</h4>
        						</div>
        						<div class="ms-3">
        							<h4 class="mb-0"><?php if ($orders):?><?=count($orders)?><?php else:?>0<?php endif;?></h4>
        							<small>Заказы</small>
        						</div>
        					</a>
        				</div>
        			</div>
        			<div class="col-md-4">
        				<div class="rounded bg-white border p-3">
        					<a href="/admin/products" class="d-flex flex-row align-items-center text-dark">
        						<div class="rounded p-3" style="background: #b7eebc;">
        							<h4 class="mb-0" style="color: #6c9970;">
        								<i class="fa-solid fa-list-ul"></i>
        							</h4>
        						</div>
        						<div class="ms-3">
        							<h4 class="mb-0"><?php if ($products):?><?=count($products)?><?php else:?>0<?php endif;?></h4>
        							<small>Товары</small>
        						</div>
        					</a>
        				</div>
        			</div>
        		</div>
        		<div class="rounded border bg-white p-3">
        			<h4 class="mb-3">Последние заказы</h4>
        			<?php if ($orders):?>
        				<div class="table-responsive">
	        				<table class="table align-middle mb-0 bg-white">
								<thead>
									<tr>
										<th>#</th>
										<th>Имя</th>
										<th>Email</th>
										<th>Сумма</th>
										<th>Дата</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($orders as $order):?>
										<tr>
											<td><?=$order['order_id']?></td>
											<td>
												<div class="d-flex align-items-center">
													<img src="/uploads/<?=$order['preview']?>" alt="" style="width: 45px; height: 45px" />
													<div class="ms-3">
														<p class="fw-bold mb-0"><?=$order['first_name']?> <?=$order['last_name']?></p>
													</div>
												</div>
											</td>
											<td>
												<p class="fw-normal mb-1"><?=$order['email']?></p>
											</td>
											<td> <span class="badge badge-success rounded-pill d-inline"><?=$order['price']?>₽</span> </td>
											<td><?=$core->actionDate($order['time'])?></td>
											<td>
												<a href="/admin/order/<?=$order['order_id']?>" class="fw-bold"> Перейти </a>
											</td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
	        			</div>
        			<?php else:?>
        				<p class="mb-0">Заказов ещё нет</p>
        			<?php endif;?>
        		</div>
        	</div>
        </main>
    </div>
    <script src="/assets/js/bootstrap.bundle.js"></script>
    <script src="/assets/js/jquery-3.3.1.min.js"></script>
	<script>
		let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
		let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  			return new bootstrap.Tooltip(tooltipTriggerEl)
		});

		$("#sidebarCollapse").click(function(e) {
			e.preventDefault();
		  	$("#wrapper").toggleClass("toggled");
		});
		
	</script>
</body>
</html>