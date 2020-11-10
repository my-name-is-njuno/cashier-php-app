<?php
	$pages = explode('/', rtrim($_GET['url'], '/'));
	if (isset($pages[2])) {
	    $page = $pages[2];
	} else {
	    $page = 1;
	}


	$total_pages = $data['total_pages'];

 ?>

<?php

	include_once include_path('header.php');
	include_once include_path('topnav.php');


?>


<main>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="mt-4">All Orders</h1>
	            
	            <ol class="breadcrumb mb-4">
	                <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                <li class="breadcrumb-item"><a href="<?php url_to('customers') ?>">All Orders</a></li>
	            </ol>
	            <?php include_once include_path('message.php'); ?>
			</div>
			
		</div>



		<div class="row mb-2">
			<div class="col-sm-6">
				<form action="<?php url_to('customers') ?>" method="post">
					<div class="input-group">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="">Filter With Dates</span>
					  </div>
					  <input type="date" name="from" required class="form-control" value="<?= $data['from'] ?>">
					  <input type="date" name="to" required class="form-control" value="<?= $data['to'] ?>">
					  <div class="input-group-append">
					   <button type="submit" class="btn btn-primary">Go</button>
					 </div>
					</div>
				</form>
				
			</div>
			<div class="col-sm-3">
				
			</div>
			
			<div  class="col-sm-3 d-flex justify-content-end">
				<a class="align-item-end btn btn-primary m-1" href="<?php url_to('customers/create') ?>">New Customer Order <i class="fa fa-plus"></i></a>
			</div>
	        
	    </div>

		<div class="row">
			<div class="col-sm-12">
				<table class="table  table-sm table-bordered">
					<thead>
						<tr>
							<th width="20%">
								Customer
							</th>
							<th width="20%">
								When
							</th>
							<th width="14%"  class="text-right">
								Totals
							</th>
							<th width="14%" class="text-right">
								Paid
							</th>
							<th width="14%" class="text-right">
								Balance
							</th>
							<th  class="text-center">
								View
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data['singleorders'] as $v): ?>
							<tr class = "<?php if ($v->receipt_balance > 0): ?>
								bg-light
							<?php endif ?>">
								<td>
									<?= $v->customer_name; ?>
								</td>
								<!-- ordering with dates -->
								<?php 
									$mydate = strtotime($v->singleorder_at);
								 ?>
								<td data-sort="<?= $mydate ?>">
									<?= formatedDate($v->singleorder_at) ?>
								</td>
								<td class="text-right" data-sort="<?= (float) $v->singleorder_total ?>">
									<?= number_format($v->singleorder_total); ?>
								</td>
								<td class="text-right" data-sort="<?= (float) $v->receipt_paid ?>">
									<?= number_format($v->receipt_paid); ?>
								</td>
								<td class="text-right" data-sort="<?= (float) $v->receipt_balance ?>">
									<?= number_format($v->receipt_balance); ?>
								</td>
								<td class="">
									<a href="<?php url_to('customers/edit/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-primary"><i class="fa fa-edit"></i></span>
									</a>
									<a href="<?php url_to('customers/show/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-success"><i class="fa fa-eye"></i></span>
									</a>
									<?php if ($v->receipt_balance > 0): ?>
										<a href="<?php url_to('customers/recievebalance/'.$v->id) ?>">
											<span class="mx-1 btn btn-sm btn-dark"><i class="fa fa-receipt"></i></span>
										</a>
									<?php endif ?>
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
				    <li class="page-item"><a class="page-link" href="<?php url_to('customers/home/1'); ?>">First</a></li>
				    <?php for ($p = max(1, $page - 5); $p <= min($page + 5, $total_pages); $p++) { ?>
				        <li class="page-item <?= $page == $p ? 'active' : ''; ?>"><a href="<?php url_to('customers/home/'.$p); ?>" class="page-link"><?= $p; ?></a></li>
				    <?php } ?>
				    ...
				    <li class="page-item"><a class="page-link" href="<?php url_to('customers/home/'.$total_pages); ?>">Last</a></li>
				</ul>
			</nav>
			</div>
		</div>
	</div>

</main>





<?php 
	include_once include_path('footer.php');
?>


