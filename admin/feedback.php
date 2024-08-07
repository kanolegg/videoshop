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

$feedback = $core->getFeedback();

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
        			<?php if ($feedback):?>
        				<div class="table-responsive" id="feedback-table">
	        				<table class="table align-middle mb-0 bg-white">
								<thead>
									<tr>
										<th></th>
										<th>Имя</th>
										<th>Email</th>
										<th>Сообщение</th>
										<th>Дата</th>
									</tr>
								</thead>
								<tbody id="feedback">
									<?php foreach($feedback as $f):?>
										<tr class="order" id="order-<?=$f['id']?>" data-order="<?=htmlspecialchars(json_encode($f, JSON_UNESCAPED_UNICODE))?>">
											<td class="fw-bold"><?=$f['id']?></td>
											<td>
												<p class="fw-normal mb-1"><?=$f['name']?></p>
											</td>
											<td>
												<p class="fw-normal mb-1 nowrap">
													<a href="mailto:<?=$f['email']?>"><?=$f['email']?></a>
												</p>
											</td>
											<td>
												<p class="fw-normal mb-1 row-cut-3"><?=$f['message']?></p>
											</td>
											<td>
												<p class="fw-normal mb-1"><?=$core->actionDate($f['time'])?></p>
											</td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
	        			</div>
        			<?php else:?>
        				<p class="mb-0">Заявок ещё нет</p>
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