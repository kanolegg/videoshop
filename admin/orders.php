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

$userData = $core->getUser($_COOKIE['id']);
if (!$userData['rank'])
	return header('Location: /');

$menu = $core->menu();

$orders = $core->getOrders(false, true);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Заказы / Управление</title>
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
        		<h2>Заказы</h2>
        		<div class="rounded border bg-white p-3" id="content">
        			<?php if ($orders):?>
        				<div class="table-responsive" id="orders-table">
	        				<table class="table align-middle mb-0 bg-white">
								<thead>
									<tr>
										<th></th>
										<th>Фото</th>
										<th>Заказчик</th>
										<th>Цена</th>
										<th>Дата</th>
										<th>Действия</th>
									</tr>
								</thead>
								<tbody id="orders">
									<?php foreach($orders as $order):?>
										<tr class="order" id="order-<?=$order['order_id']?>" data-order="<?=htmlspecialchars(json_encode($order, JSON_UNESCAPED_UNICODE))?>">
											<td>#<?=$order['order_id']?></td>
											<td>
												<div class="d-flex align-items-center">
													<img src="/uploads/<?=$order['preview']?>" alt="" style="max-height: 4rem;" />
												</div>
											</td>
											<td>
												<p class="fw-normal mb-1"><?=$order['first_name']?> <?=$order['last_name']?></p>
											</td>
											<td>
												<p class="fw-normal mb-1 nowrap"><?=number_format($order['price'], 2, ',', ' ')?>₽</p>
											</td>
											<td>
												<p class="fw-normal mb-1"><?=$core->actionDate($order['time'])?></p>
											</td>
											<td>
												<div class="d-flex" style="column-gap: 8px;">
													<div>
														<a href="/admin/order/<?=$order['order_id']?>" class="btn btn-warning rounded-pill" data-bs-toggle="tooltip" data-bs-placement="top" title="Детали заказа">
															<i class="fa-light fa-file-magnifying-glass"></i>
														</a>
													</div>
													<button class="btn btn-info rounded-pill delivery" style="white-space: nowrap;" data-id="<?=$order['id']?>">Передать в доставку</button>
												</div>
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
        		<p class="text-muted ms-2">
        			<i class="fa-light fa-circle-info"></i>
        			<span>Обратите внимание, что здесь показываются только оплаченные заказы</span>
        		</p>
        	</div>
        </main>
    </div>
    <div class="modal fade" id="deliveryModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Информация</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<h4>Заказ успешно передан в доставку</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
				</div>
			</div>
		</div>
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

		$(".delivery").on("click", function() {
			let order = $(this).closest(".order").data("order");

			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'orderStatusUpdate',
                    'params': {
                    	'status': 2,
                    	'user_id': order.user_id,
                        'order_id': order.order_id
                    }
                },
                success: function(response) {
                	console.log(response)
                    response = JSON.parse(response);
                    if (!response.error) {
                    	$("#order-"+order.order_id).remove();
                    	$("#deliveryModal").modal("toggle");
                    	if ($("#orders").children().length === 0) {
                    		$("#orders-table").remove();
                    		jQuery('<p>', {
                    			'class': 'mb-0'
                    		}).text("Заказов ещё нет").appendTo("#content");
                    	}
                    } else {
                    	console.log(response.message);
                    }
                }
            });
		});
	</script>
</body>
</html>