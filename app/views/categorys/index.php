<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

	<main>
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h1>
						Menu Categories (<?= $data['categorys']['count'] ?>)
					</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb mb-4">
	                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                    <li class="breadcrumb-item"><a href="<?php url_to('categorys') ?>">Our Menu Categories</a></li>
	                </ol>
				</div>
			</div>

			
			
			<?php if (get_sess('logged_in_user_role') == 1): ?>
			  <div class="row mb-2 d-flex justify-content-end">
	              <a class="align-item-end btn btn-dark m-1" href="<?php url_to('categorys/create') ?>">New Menu Category <i class="fa fa-plus"></i></a>
	          </div>
	        <?php endif ?>



			<div class="row">
				<div class="col-sm-12">
					
					<table class="table table-sm table-bordered">
					<thead>
						<tr>
							<th width="60%">
								Category Name
							</th>
							
							<th width="20%">
								Menu Items
							</th>
							
							<th class="text-center">
								Actions
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data['categorys']['data'] as $v): ?>
							<tr>
								<td>
									<?= $v->category_name; ?>
								</td>
								
								<td class="">
									<?= $v->cnt; ?>
								</td>
								
								<td class="text-center">
									<a href="<?php url_to('categorys/edit/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-primary"><i class="fa fa-edit"></i></span>
									</a>
									<a href="<?php url_to('categorys/show/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-success"><i class="fa fa-eye"></i></span>
									</a>
									<?php if ($v->cnt <= 0): ?>
										<a href="<?php url_to('categorys/delete/'.$v->id) ?>">
										<span class="mx-1 btn btn-sm btn-danger"><i class="fa fa-eye"></i></span>
									</a>
									<?php endif ?>
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