<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

	




	<main>
		
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h1>
						<?= $data['category']->category_name ?> Menu Items (<?= $data['menus']['count'] ?>)
					</h1>
				</div>
			</div>
			<?php include_once include_path('message.php'); ?>
			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb mb-4">
	                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                    <li class="breadcrumb-item"><a href="<?php url_to('categorys') ?>">Our Menu Categories</a></li>
	                    <li class="breadcrumb-item"><a href="<?php url_to('menus/create') ?>"><?= $data['category']->category_name ?> Menu Items</a></li>
	                </ol>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					
					<table class="table table-sm table-bordered">
					<thead>
						<tr>
							<th width="40%">
								Menu Item
							</th>
							
							<th width="10%">
								Category
							</th>
							<th width="14%"  class="text-right">
								Cost
							</th>
							<th width="14%" class="text-right">
								S. Price
							</th>
							<th class="text-center">
								Actions
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data['menus']['data'] as $v): ?>
							<tr>
								<td>
									<?= $v->menu_item; ?>
								</td>
								
								<td class="">
									<?= $v->category_name; ?>
								</td>
								<td class="text-right" data-sort="<?= (float) $v->menu_cost ?>">
									<?= number_format($v->menu_cost); ?>
								</td>
								<td class="text-right" data-sort="<?= (float) $v->menu_price ?>">
									<?= number_format($v->menu_price); ?>
								</td>
								<td class="text-center">
									<a href="<?php url_to('menus/edit/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-primary"><i class="fa fa-edit"></i></span>
									</a>
									<a href="<?php url_to('menus/show/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-success"><i class="fa fa-eye"></i></span>
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
	</main>






<?php 
	include_once include_path('footer.php');
?>