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
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h1 class="mt-4">All Receipts</h1>
	            <?php 
					include_once include_path('message.php');
				?>
	            <ol class="breadcrumb mb-4">
	                <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                <li class="breadcrumb-item"><a href="<?php url_to('receipts') ?>">All Orders</a></li>
	            </ol>
			</div>
			
		</div>




		<div class="row">
			<div class="col-sm-12">
				<table class="table">
					<thead>
						<tr>
							<th width="5%">
								#
							</th>
							<th width="30%">
								Customer Name
							</th>
							<th width="30%">
								When
							</th>
							<th width="20%">
								Totals
							</th>
							<th>
								View
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data['singleorders'] as $v): ?>
							<tr>
								<td>
									<?= $v->receipt_name; ?>
								</td>
								<td>
									<?= formatedDate($v->singleorder_at) ?>
								</td>
								<td>
									Kes <?= number_format($v->singleorder_total); ?>
								</td>
								<td>
									<a href="<?php url_to('receipts/edit/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-primary"><i class="fa fa-edit"></i></span>
									</a>
									<a href="<?php url_to('receipts/show/'.$v->id) ?>">
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
				<ul class="pagination">
				    <li><a href="<?php url_to('diagnoses/home/1'); ?>">First</a></li>
				    <?php for ($p = max(1, $page - 5); $p <= min($page + 5, $total_pages); $p++) { ?>
				        <li class="<?= $page == $p ? 'active' : ''; ?>"><a href="<?php url_to('diagnoses/home/'.$p); ?>"><?= $p; ?></a></li>
				    <?php } ?>
				    ...
				    <li><a href="<?php url_to('diagnoses/home/'.$total_pages); ?>">Last</a></li>
				</ul>
			</div>
		</div>
	</div>

</main>





<?php 
	include_once include_path('footer.php');
?>