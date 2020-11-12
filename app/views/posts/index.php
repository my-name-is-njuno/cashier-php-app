
<?php

	include_once include_path('header.php');
	include_once include_path('topnav.php');


?>


<?php
	$pages = explode('/', rtrim($_GET['url'], '/'));
	if (isset($pages[2])) {
	    $page = $pages[2];
	} else {
	    $page = 1;
	}


	$total_pages = $data['total_pages'];

 ?>


<main>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="mt-4">All Menus</h1>
	            <?php 
					include_once include_path('message.php');
				?>
	            <ol class="breadcrumb mb-4">
	                <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                <li class="breadcrumb-item"><a href="<?php url_to('menus') ?>">Our Menu</a></li>
	            </ol>
			</div>
			
		</div>



		<div class="row mb-2 d-flex justify-content-end">
			<?php if (get_sess('logged_in_user_role') == 1): ?>
				<a class="align-item-end btn btn-primary m-1" href="<?php url_to('menus/create') ?>">New Menu Item <i class="fa fa-plus"></i></a>
			<?php endif ?>
	        
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
						<?php foreach ($data['menus'] as $v): ?>
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




		<div class="row">
			<div class="col-sm-12">
				<nav aria-label="Page navigation example">
				<ul class="pagination">
				    <li class="page-item"><a class="page-link" href="<?php url_to('menus/home/1'); ?>">First</a></li>
				    <?php for ($p = max(1, $page - 5); $p <= min($page + 5, $total_pages); $p++) { ?>
				        <li class="page-item <?= $page == $p ? 'active' : ''; ?>"><a href="<?php url_to('menus/home/'.$p); ?>" class="page-link"><?= $p; ?></a></li>
				    <?php } ?>
				    ...
				    <li class="page-item"><a class="page-link" href="<?php url_to('menus/home/'.$total_pages); ?>">Last</a></li>
				</ul>
			</nav>
			</div>
		</div>



		
	</div>

</main>





<?php 
	include_once include_path('footer.php');
?>