<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>




	<main>
            <div class="container">
                <h1 class="mt-4">Add New Menu Item</h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('menus') ?>">Our Menu</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('menus/create') ?>">New Menu Item</a></li>
                </ol>

                <?php include_once include_path('message.php'); ?>

                <div class="card mb-4">
                <div class="card-body">
                  
				<div class="row justify-content-center">
				  <div class="col-sm-8 justify-content-center">
                      <form method="POST" enctype="multipart/form-data" action="<?php url_to('menus/create'); ?>">
                      		<div class="form-group">
								<label for="my-input">Menu Item</label>
								<input type="text" name="menu_item" class="form-control form-control-lg" value="<?php echo $data['menu_item']; ?>" required>
								<span class="form-text text-danger"><?php echo $data['menu_item_err'] ?></span>
							</div>


							<div class="form-group">
								<label for="my-input">Menu Description</label>
								<textarea type="text" name="menu_description" class="form-control form-control-lg" required><?php echo $data['menu_description']; ?></textarea>
								<span class="form-text text-danger"><?php echo $data['menu_item_err'] ?></span>
							</div>

	



							<div class="form-group">
								<label for="my-input">Menu Image</label>
								<input type="file" name="menu_image" class="form-control-file form-control-lg" id="menu_image">
								<span class="form-text text-danger"><?php echo $data['menu_image_err'] ?></span>
							</div>



					


							

							<div class="form-group">
								<label for="my-input">Cost</label>
								<input type="number" name="menu_cost" class="form-control form-control-lg" value="<?php echo $data['menu_cost']; ?>" required>
								<span class="form-text text-danger"><?php echo $data['menu_cost_err'] ?></span>
							</div>




							<div class="form-group">
								<label for="my-input">Selling Price</label>
								<input type="number" name="menu_price" value="<?php echo $data['menu_price']; ?>" class="form-control form-control-lg" required>
								<span class="form-text text-danger"><?php echo $data['menu_price_err'] ?></span>
							</div>



							<div class="form-group">
								<label for="my-select">Category</label>
								<select id="my-select" class="custom-select" name="menu_category_id">
									<option value="">Choose Category</option>
									<?php foreach ($data['categorys'] as $v): ?>
										<option value="<?= $v->id ?>" <?php if ($v->id == $data['menu_category_id']): echo 'selected'; ?><?php endif ?>
										><?= $v->category_name ?></option>
									<?php endforeach ?>
								</select>
								<span class="form-text text-danger"><?php echo $data['menu_category_id_err'] ?></span>
							</div>



							<button type="submit" class="btn btn-lg btn-primary">Add New Menu Item</button>

                      </form>
	              </div>
				  </div>
                  
	          	</div>
	        </div>
	    </div>
	</main>















<?php 
	include_once include_path('footer.php');
?>