<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>

	<div class="container">

		<div class="row">
			<div class="col-sm-12">
				 <h1 class="mt-4">
				 	<?php if (!$data['items']['count']): ?>
				 		Delete <?= $data['category']->category_name ?>
				 	<?php else: ?>
				 		<?= $data['category']->category_name ?> Cannot be deleted.
				 	<?php endif ?>
				 </h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('categorys') ?>">All Menu Categories</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('category/delete/'.$data['category']->id) ?>">Delete <?= $data['category']->category_name ?> category </a></li>
                </ol>
			</div>
		</div>


		<?php include_once include_path('message.php'); ?>



		<?php if ($data['items']['count'] == 0): ?>
			<center>
				
				<form class="mb-3" method="post" action="<?php url_to('categorys/delete/'.$data['category']->id); ?>">
					<p>Are You sure you want to delete this menu category??</p>
					<button type="submit" class="btn btn-danger">Yes, Delete category please</button>
				</form>
				<button class="btn btn-success"><a href="<?php url_to('categorys/show/'.$data['category']->id);?>" class="text-white">No, Take me back</a></button>
			</center>


		<?php else: ?>
			<div>
				

				<h4>
					This category has <?= $data['items']['count']; ?> items.
				</h4>
				
				

				
				<p class="lead"><?= $data['category']->category_name ?> has menu items records so cannot be deleted.</p>
				<ul>
					<?php foreach ($data['items']['data'] as $value): ?>
						<li>
							<a href="<?php url_to('menus/show/'.$value->id) ?>" class=""><?= $value->menu_item; ?></a>
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