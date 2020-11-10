<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1>
					Edit <?php echo $data['category']->category_name ?>
				</h1>				<ol class="breadcrumb mb-4">
	                <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                <li class="breadcrumb-item"><a href="<?php url_to('menus') ?>">Our Menu</a></li>
	            </ol>
			</div>
		</div>

		<?php include_once include_path('message.php'); ?>


		<div class="row justify-content-center">
			<div class="col-sm-8">
				<form method="POST" action="<?php url_to('categorys/edit/'.$data['category']->id); ?>">
					<div class="form-group">
						<label for="">Category Name</label>
						<input type="text" name="category_name" class="form-control" value="<?php echo $data['category_name']; ?>">
					</div>
					<div class="form-group">
						 <button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>









<?php 
	include_once include_path('footer.php');
?>