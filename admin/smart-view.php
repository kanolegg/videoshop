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

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} else {
	return header('Location: /admin/smart');
}

$menu = $core->menu();

$request = $core->getSmartHomeRequest($id);
$features = (array) json_decode($request['features']);
$feat = [1 => 'Умный дом под ключ', 2 => 'Управление светом', 3 => 'Управление климатом', 4 => 'Видеонаблюдение', 5 => 'Кинотеатр', 6 => 'Вентиляция'];
$management = [1 => 'Смартфон/планшет', 2 => 'ИИ', 3 => 'Облачные технологии', 4 => 'Голосовое управление'];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Заявка #<?=$request['id']?> / Управление</title>
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
        			<div class="d-flex align-items-center">
	        			<a href="/admin/smart" class="text-info">
		        			<i class="fa-solid fa-chevron-left" style="font-size: 24px"></i>
		        		</a>
		        		<h2 class="ms-3">Заявка #<?=$request['id']?></h2>
	        		</div>
        		</div>
        		<div class="rounded border bg-white p-3" id="content">
        			<div class="row">
        				<div class="col-xl-6">
        					<div class="table-responsive">
		        				<table class="table">
		        					<tr>
		        						<td>Имя</td>
		        						<td><?=$request['name']?></td>
		        					</tr>
		        					<tr>
		        						<td>Телефон</td>
		        						<td><?=$request['phone']?></td>
		        					</tr>
		        					<tr>
		        						<td>Дата</td>
		        						<td><?=$core->actionDate($request['date'])?></td>
		        					</tr>
		        					<tr>
		        						<td>Статус</td>
		        						<td><?php if ($request['status'] === 0):?><div class="badge badge-warning rounded-pill">Активен</div><?php else:?><div class="badge badge-success rounded-pill">Закрыт</div><?php endif;?></td>
		        					</tr>
		        					<tr>
		        						<td>Расположение</td>
		        						<td><?php if ($request['place'] === 0):?>Квартира<?php else:?>Коттедж<?php endif;?></td>
		        					</tr>
		        					<tr>
		        						<td>Квадратура</td>
		        						<td><?=$request['area']?>м<sup>2</sup></td>
		        					</tr>
		        					<tr>
		        						<td>Возможности</td>
		        						<td>
		        							<div class="d-flex flex-wrap" style="column-gap: 8px; grid-row-gap: 8px;">
			        							<?php foreach ($features as $feature):?>
			        								<div class="badge badge-info rounded-pill"><?=$feat[$feature]?></div>
			        							<?php endforeach;?>
			        						</div>
		        						</td>
		        					</tr>
		        					<tr>
		        						<td>Управление</td>
		        						<td>
		        							<?=$management[$request['management']]?>
		        						</td>
		        					</tr>
		        				</table>
		        			</div>
        				</div>
        			</div>
        			<?php if ($request['status'] === 0):?>
        				<button type="button" id="close" class="btn btn-warning">Закрыть заявку</button>
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

		$("#close").on("click", function(e) {
			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'smartHomeClose',
                    'params': {
                        'id': <?=$id?>
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                    	window.location.href = '/admin/smart';
                    } else {
                    	console.log(response.message);
                    }
                }
            });
		});
	</script>
</body>
</html>