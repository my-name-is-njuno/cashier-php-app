<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>

	<div class="container">

		<?php if ($data['sales']['count'] == 0): ?>
			<center>
				<h1>
					Delete <?= $data['menu']->menu_item ?>
				</h1>
				
				<?php include_once include_path('message.php'); ?>

				<hr>
				<form class="mb-3" method="post" action="<?php url_to('menus/delete/'.$data['menu']->id); ?>">
					<p>Are You sure you want to delete this menu??</p>
					<button type="submit" class="btn btn-danger">Yes, Delete menu please</button>
				</form>
				<button class="btn btn-success"><a href="<?php url_to('menus/show/'.$data['menu']->id);?>" class="text-white">No, Take me back</a></button>
			</center>


		<?php else: ?>
			<div>
				<h1 class="text-center">
					<?= $data['menu']->menu_item ?> Cannot be deleted.
				</h1>

				<h4>
					This Menu has <?= $data['sales']['count']; ?> Sales.
				</h4>
				
				<?php include_once include_path('message.php'); ?>

				<hr>
				<p class="lead"><?= $data['menu']->menu_item ?> has sales records so cannot be deleted.</p>
				<ul>
					<?php foreach ($data['sales']['data'] as $value): ?>
						<li>
							<a href="<?php url_to('customers/show/'.$value->order_singleorder_id) ?>" class=""><?= $value->customer_name; ?></a>
						</li>
					<?php endforeach ?>
					
				</ul>
			</div>
		<?php endif ?>
	



	</div>
</main>




<?php 
	include_once include_path('footer.php');
?>