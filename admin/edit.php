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
    $product = $core->getProduct(mb_ereg_replace('[^0-9]', '', $id));
    if ($product) {
        $category = $core->getCategoryByUrl($product['category']);

        $specs = (array) json_decode($product['specs']);

        $params = [];

        $related = json_decode($product['related']);
        if ($related) {
            $related_str = ''; $s = '';
            foreach ($related as $r) {
                $related_str .= $s . '\''.$r.'\'';
                $s = ',';
            }
            $related = $core->getProductsByIds($related_str);
        }
        $catalog = $core->getCatalog();
    }
} else {
    header('Location: /admin/product');
}

$menu = $core->menu();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/admin/assets/css/style.css?<?=$_SERVER['REQUEST_TIME']?>">
	<link rel="stylesheet" href="/assets/fontawesome-pro-6.1.0-web/css/all.min.css">
	<title>Добавление товара / Управление</title>
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
        	<div class="navbar p-4 d-flex justify-content-between shadow-sm align-items-center">
        		<button type="button" id="sidebarCollapse" class="btn btn-info">
        			<i class="fas fa-align-left"></i>
        		</button>
        		<div class="navbar-brand fw-bold fs-4">Security</div>
        	</div>
        	<section class="py-3">
        		<div class="container">
	        		<h2>Добавление товара</h2>
	        		<div class="rounded border bg-white p-3" id="content">
	        			<form method="POST" id="productForm">
	        				<h4>Основные параметры</h4>
	        				<div class="row">
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<label class="form-label" for="name">Название</label>
		        						<input type="text" name="name" id="name" autocomplete="off" class="form-control" value="<?=$product['name']?>">
		        					</div>
		        				</div>
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<label class="form-label" for="type">Тип</label>
		        						<input type="text" name="type" id="type" autocomplete="off" class="form-control" value="<?=$product['type']?>">
		        					</div>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-md-4">
		        					<div class="mb-3">
		        						<label class="form-label" for="model">Модель</label>
		        						<input type="text" name="model" id="model" autocomplete="off" class="form-control" value="<?=$product['model']?>">
		        					</div>
		        				</div>
		        				<div class="col-md-4">
		        					<div class="mb-3">
		        						<label class="form-label" for="price">Цена</label>
		        						<input type="text" name="price" id="price" autocomplete="off" class="form-control" value="<?=$product['price']?>">
		        					</div>
		        				</div>
		        				<div class="col-md-4">
		        					<div class="mb-3">
		        						<label class="form-label" for="article">Артикул</label>
		        						<input type="text" name="article" id="article" autocomplete="off" class="form-control" value="<?=$product['article']?>">
		        					</div>
		        				</div>
		        			</div>
		        			<div class="row">
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<label class="form-label" for="manufacturer">Производитель</label>
		        						<input type="text" name="manufacturer" id="manufacturer" autocomplete="off" class="form-control" value="<?=$product['manufacturer']?>">
		        					</div>
		        				</div>
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<label class="form-label" for="amount">Количество</label>
		        						<input type="number" value="1" name="amount" id="amount" autocomplete="off" class="form-control" value="<?=$product['amount']?>">
		        					</div>
		        				</div>
		        			</div>
		        			<div class="mb-3">
		        				<label class="form-label" for="description">Описание</label>
		        				<textarea name="description" id="description" class="form-control"><?=$product['description']?></textarea>
		        			</div>
		        			<h4>Изображение</h4>
		        			<div class="row">
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<input type="file" name="photo" id="image-input" accept="image/*" class="form-control">
		        						<input type="hidden" name="image" id="image" value="<?=$product['image']?>">
		        					</div>
		        				</div>
		        				<div class="col-md-6">
		        					<div id="image-wrapper">
		        						<img src="/uploads/<?=$product['image']?>" class="img-fluid">
		        					</div>
		        				</div>
		        			</div>
		        			<h4>Сопутствующие товары</h4>
		        			<div class="row">
		        				<div class="col-md-6">
		        					<div class="mb-3">
		        						<label class="form-label" for="related">Список через запятую</label>
		        						<input type="text" name="related" id="related" autocomplete="off" class="form-control" value="<?=implode(',',json_decode($product['related']))?>">
		        					</div>
		        				</div>
		        			</div>
			        		<div class="row mb-3">
			        			<div class="col-md-6">
			        				<label class="form-label" for="amount">Категория</label>
			        				<select class="form-select" name="category" id="category">
			        					<option disabled hidden>Категория</option>
			        					<?php foreach($catalog as $category):?>
			        						<option <?php ($product['category'] === $category['url'])?'selected':''?> value="<?=$category['url']?>"><?=$category['title']?></option>
			        					<?php endforeach;?>
			        				</select>
			        			</div>
			        		</div>
	        			</form>
	        			<h4>Характеристики</h4>
		        		<div class="row g-3 mb-3" id="specs">
		        			<?php foreach($specs as $title => $spec):?>
		        				<div class="col-md-6">
		        					<label class="form-label"><?=$title?></label>
		        					<input type="text" class="form-control spec" id="<?=$title?>" value="<?=$spec?>">
		        				</div>
		        			<?php endforeach;?>
		        		</div>
		        		<hr>
		        		<div class="d-flex justify-content-end">
		        			<button class="btn btn-success" id="save">Сохранить</button>
		        		</div>
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

		$("#save").on("click", function() {
			let $inputs = $("#productForm :input").not("#image-input"), values = {}, params = {};

			for (input of $inputs) {
				if ($(input).val() === '') {
					$(input).focus();
				}

				params[input.name] = $(input).val();
			}

			let specs = {};

			$.each($(".spec"), function(i, n) {
				specs[$(n).attr("id")] = $(n).val();
            });

            params.specs = JSON.stringify(specs);

			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'updateProduct',
                    'params': params,
                    'product_id': <?=$id?>
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                        window.location.href = "/admin/products";
                    }
                }
            });
		});

		$('#image-input').change(function() {
		    let formData = new FormData(document.getElementById("productForm"));

		    $.ajax({
		        type: 'POST',
		        url: '/handler.php',
		        data: formData,
		        cache: false, // В запросах POST отключено по умолчанию, но перестрахуемся
		        contentType: false, // Тип кодирования данных мы задали в форме, это отключим
		        processData: false, // Отключаем, так как передаем файл
		        success: function(data) {
		            response = JSON.parse(data);
		            if (response.name) {
		                readImage(data);
		            }
		        },
		        error: function(data) {
		            console.log(data);
		        }
		    });
		});

		function readImage(data) {
		    photo = JSON.parse(data);
		    $("#image-wrapper").empty();
		    jQuery('<img>', {
		        'class': 'img-fluid',
		        "src": "/uploads/"+photo.name+"."+photo.ext
		    }).appendTo($("#image-wrapper"));

		    $("#image").val(photo.name+"."+photo.ext);
		};

		$("#category").on("change", function() {
			let url = $(this).val();
			$.ajax({
                url: '/handler',
                method: 'post',
                dataType: 'html',
                data: {
                    'method': 'getCategoryById',
                    'params': {
                        'url': url
                    }
                },
                success: function(response) {
                    response = JSON.parse(response);
                    if (!response.error) {
                    	$("#specs").empty();
                    	let specs = JSON.parse(response.category.specs);

                    	for (let spec of specs) {
                    		let wrapper = jQuery('<div>', {
                    			'class': 'col-md-6'
                    		}).appendTo("#specs");

                    		jQuery('<label>', {
						        'class': 'form-label'
						    }).text(spec).appendTo($(wrapper));
                    		
                    		jQuery('<input>', {
						        'class': 'form-control spec',
						        'id': spec
						    }).appendTo($(wrapper));
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