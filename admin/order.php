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

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	return header('Location: /admin/orders');
}

if ($order = $core->getOrder($id)) {
	$products_list = (array) json_decode($order['products']);
	$s = '';
	$ids = '';
	foreach ($products_list as $p => $amount) {
		$ids .= $s.$p;
		$s = ',';
	}
	$products = $core->getProductsByIds($ids);
} else {
	return header('Location: /admin/orders');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Заказ #<?=$order['id']?> / Управление</title>
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
        		<div class="rounded border bg-white p-3 mb-3">
        			<div class="d-flex justify-content-between align-items-center">
	        			<div class="d-flex align-items-center">
	        				<a href="/admin/orders" class="text-info">
			        			<i class="fa-solid fa-chevron-left" style="font-size: 24px"></i>
			        		</a>
	        				<h2 class="ms-3">Заказ #<?=$order['id']?></h2>
	        				<h5 class="mb-1 ms-2"><?=$core->getOrderStatusBadge($order['status'])?></h5>
	        			</div>
	        			<div class="text-muted"><?=$core->actionDate($order['time']);?></div>
	        		</div>
        		</div>
        		<div class="rounded border bg-white p-3" id="content">
        			<div class="row">
        				<div class="col-md-6">
        					<div class="mb-3">
        						<label class="form-label" for="name">Получатель</label>
        						<input type="text" id="name" class="form-control" disabled value="<?=$order['first_name']?> <?=$order['last_name']?>">
        					</div>
        				</div>
        			</div>
        			<h4>Позиции заказа:</h4>
        			<div class="row">
        				<?php foreach ($products as $p):?>
	        				<div class="col-md-6">
	        					<div class="rounded border p-3">
	        						<div class="row">
	        							<div class="col-md-3">
	        								<img src="/uploads/<?=$p['image']?>" class="img-fluid">
	        							</div>
	        							<div class="col-md-9">
	        								<div class="d-flex flex-column justify-content-between h-100">
	        									<a href="/product/<?=$p['id']?>" target="_blank" class="fw-bold row-cut-2"><?=$p['name']?></a>
	        									<div class="text-muted">Кол-во: <span class="fw-bold"><?=$products_list[$p['id']]?></span></div>
	        									<div class="text-muted">Цена: <span class="fw-bold"><?=number_format($p['price'], 0, ',', ' ')?>₽</span></div>
	        								</div>
	        							</div>
	        						</div>
	        					</div>
	        				</div>
        				<?php endforeach;?>
        			</div>
        			<hr>
        			<div class="d-flex justify-content-between">
        				<h4>Итого:</h4>
	        			<h4 class="fw-bold"><?=number_format($order['price'], 0, ',', ' ')?>₽</h4>
	        		</div>
        		</div>
        	</div>
        </main>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Подтверждение</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<h4>Вы действительно хотите удалить категорию?</h4>
					<input type="hidden" id="deleteId">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
					<button type="button" class="btn btn-danger" id="delete">Да</button>
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

		$(".delete").on("click", function(e) {
			e.preventDefault();
			let id = $(this).data("id");

			$("#deleteId").val(id);
			$("#deleteModal").modal("toggle");
		});

		$("#delete").on("click", function() {
			let id = $("#deleteId").val();

			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'productDelete',
                    'params': {
                        'id': id
                    }
                },
                success: function(response) {
                	console.log(response)
                    response = JSON.parse(response);
                    if (!response.error) {
                    	$("#category-"+id).remove();
                    	$("#deleteModal").modal("toggle");

                    	if ($("#categories").children().length === 0) {
                    		$("#categories-table").remove();
                    		jQuery('<p>', {
						        'class': 'mb-0'
						    }).text("Категорий ещё нет").appendTo($("#content"));
						    $("#deleteModal").modal("toggle");
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