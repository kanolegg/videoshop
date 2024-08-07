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

$requests = $core->getSmartHome();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Заявки / Управление</title>
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
        		<h2>Заявки</h2>
        		<div class="rounded border bg-white p-3" id="content">
        			<?php if ($requests):?>
        				<div class="table-responsive" id="requests-table">
	        				<table class="table align-middle mb-0 bg-white">
								<thead>
									<tr>
										<th></th>
										<th>Заказчик</th>
										<th>Телефон</th>
										<th>Статус</th>
										<th>Дата</th>
										<th></th>
									</tr>
								</thead>
								<tbody id="requests">
									<?php foreach($requests as $request):?>
										<tr id="request-<?=$request['id']?>">
											<td>#<?=$request['id']?></td>
											<td>
												<p class="mb-0"><?=$request['name']?></p>
											</td>
											<td>
												<p class="fw-normal mb-0"><?=$request['phone']?></p>
											</td>
											<td class="status-wrapper">
												<?php if ($request['status'] === 0):?><div class="badge badge-warning">Активен</div><?php else:?><div class="badge badge-success">Закрыт</div><?php endif;?>
											</td>
											<td>
												<p class="fw-normal mb-0"><?=$core->actionDate($request['date'])?></p>
											</td>
											<td>
												<div class="d-flex flex-row" style="column-gap: 8px;">
													<?php if ($request['status'] === 0):?>
													<button type="button" class="fw-bold btn btn-success close" data-id="<?=$request['id']?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Закрыть">
														<i class="fa-light fa-file-check"></i>
													</button>
												<?php endif;?>
													<a href="/admin/smart/<?=$request['id']?>" class="fw-bold btn btn-primary open" data-id="<?=$request['id']?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Перейти">
														<i class="fa-light fa-arrow-right"></i>
													</a>
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
        	</div>
        </main>
    </div>
    <div class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Информация</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<h4>Заявка помечена как закрытая</h4>
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

		$(".close").on("click", function(e) {
			let id = $(this).data("id");
			let btn = $(this);

 			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'smartHomeClose',
                    'params': {
                        'id': id
                    }
                },
                success: function(response) {
                	console.log(response)
                    response = JSON.parse(response);
                    if (!response.error) {
                    	$("#request-"+id).find(".status-wrapper").empty();
						$("#request-"+id).find(".status-wrapper").empty();
						jQuery("<div>", {
							'class': 'badge badge-success'
						}).text("Закрыт").appendTo($("#request-"+id).find(".status-wrapper"));
						$(btn).remove();
						$.each($('.tooltip'), function (index, element) {
			    			$(this).remove();
			 			});
                    } else {
                    	console.log(response.message);
                    }
                }
            });
		});
	</script>
</body>
</html>