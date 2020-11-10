<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>

	<div class="container">

		<div class="row">
			<div class="col-sm-12">
				 <h1 class="mt-4">
				 	<?php if (!$data['users']['count']): ?>
				 		Delete <?= $data['role']->role_name ?>
				 	<?php else: ?>
				 		<?= $data['role']->role_name ?> Cannot be deleted.
				 	<?php endif ?>
				 </h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('roles') ?>">All Menu Categories</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('roles/delete/'.$data['role']->id) ?>">Delete <?= $data['role']->role_name ?> role </a></li>
                </ol>
			</div>
		</div>


		<?php include_once include_path('message.php'); ?>



		<?php if ($data['users']['count'] == 0): ?>
			<center>
				
				<form class="mb-3" method="post" action="<?php url_to('roles/delete/'.$data['role']->id); ?>">
					<p>Are You sure you want to delete this menu role??</p>
					<button type="submit" class="btn btn-danger">Yes, Delete role please</button>
				</form>
				<button class="btn btn-success"><a href="<?php url_to('roles/show/'.$data['role']->id);?>" class="text-white">No, Take me back</a></button>
			</center>


		<?php else: ?>
			<div>
				

				<h4>
					This role has <?= $data['users']['count']; ?> users.
				</h4>
				
				

				
				<p class="lead"><?= $data['role']->role_name ?> has users records so cannot be deleted.</p>
				<ul>
					<?php foreach ($data['users']['data'] as $value): ?>
						<li>
							<a href="<?php url_to('users/profile/'.$value->id) ?>" class=""><?= $value->user_name; ?></a>
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