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

if (isset($_GET)) {
    $where = '';

    if ($_GET['category'] && $_GET['category'] !== 'all')
    	$where = $core->addWhere($where, "`category` = '{$_GET['category']}'");

    if ($_GET['query']) {
        $where = $core->addWhere($where, "`name` LIKE '%".htmlspecialchars($_GET['query']))."%'";
    }

    $sql = "SELECT * FROM `products`";
    if ($where) $sql .= " WHERE $where";
    $products = $core->search($sql);
} else {
    $products = $core->getProducts($category['url']);
}

$catalog = $core->getCatalog();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Товары / Управление</title>
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
        	<section class="pb-3">
        		<div class="container">
	        		<div class="border rounded bg-white p-3 mb-3">
	        			<div class="d-flex justify-content-between align-items-center mb-1">
	        				<h2 class="mb-0">Товары</h2>
	        				<div class="d-flex" style="column-gap: 8px">
	        					<button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#filters" aria-expanded="false" aria-controls="filters">
	        						<i class="fa-light fa-filter"></i>
	        					</button>
		        				<a href="/admin/create" class="btn btn-success">
		        					<i class="fa-regular fa-plus"></i>
		        				</a>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="border rounded bg-white p-3 mb-3 collapse <?php if (!empty($_GET)):?>show<?php endif;?>" id="filters">
	        			<form method="GET" class="d-flex" style="column-gap: 8px">
		        			<input type="text" name="query" id="search" autocomplete="off" class="form-control" placeholder="Поиск" value="<?=($_GET['query'])?$_GET['query']:''?>">
		        			<select class="form-select" id="category-filter" name="category">
		        				<option selected value="all">Все</option>
		        				<?php foreach ($catalog as $category):?>
		        					<option value="<?=$category['url']?>" <?php if ($category['url'] === $_GET['category']):?>selected<?php endif;?>><?=$category['title']?></option>
		        				<?php endforeach;?>
		        			</select>
		        			<button class="btn btn-success">
		        				<i class="fa-light fa-magnifying-glass"></i>
		        			</button>
	        			</form>
	        		</div>
	        		<div class="rounded border bg-white p-3" id="content">
	        			<?php if ($products):?>
	        				<div class="table-responsive" id="categories-table">
		        				<table class="table align-middle mb-0 bg-white">
									<thead>
										<tr>
											<th></th>
											<th>Фото</th>
											<th>Название</th>
											<th>Цена</th>
											<th>Производитель</th>
											<th>Действия</th>
										</tr>
									</thead>
									<tbody id="categories">
										<?php foreach($products as $product):?>
											<tr data-category="<?=$product['category']?>" id="category-<?=$product['id']?>">
												<td><?=$product['id']?></td>
												<td>
													<div class="d-flex align-items-center">
														<img src="/uploads/<?=$product['image']?>" alt="" style="max-height: 4rem;" />
													</div>
												</td>
												<td>
													<a href="/product/<?=$product['id']?>" class="fw-bold mb-1 row-cut-2 name"><?=$product['name']?></a>
													<p class="fw-normal mb-1 article"><?=$product['article']?></p>
												</td>
												<td>
													<p class="fw-normal mb-1 nowrap"><?=number_format($product['price'], 0, ',', ' ')?>₽</p>
												</td>
												<td>
													<p class="fw-normal mb-1"><?=$product['manufacturer']?></p>
												</td>
												<td>
													<div class="d-flex" style="column-gap: 8px;">
														<a href="/product/<?=$product['id']?>" class="fw-bold btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Перейти" target="_blank">
															<i class="fa-regular fa-link" style="font-size: 20px;"></i>
														</a>
														<a href="/admin/edit/<?=$product['id']?>" class="fw-bold btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Редактировать">
															<i class="fa-regular fa-file-pen" style="font-size: 20px;"></i>
														</a>
														<a href="#" class="fw-bold btn btn-danger delete" data-id="<?=$product['id']?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Удалить">
															<i class="fa-regular fa-trash" style="font-size: 20px;"></i>
														</a>
													</div>
												</td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
		        			</div>
	        			<?php else:?>
	        				<p class="mb-0">Поиск не дал результатов</p>
	        			<?php endif;?>
	        		</div>
	        	</div>
        	</section>
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