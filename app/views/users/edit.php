<?php
include_once include_path('header-admin.php');
include_once include_path('sidenav-admin.php');
include_once include_path('topnav-admin.php');
?>







<main>
            <div class="container">
                <h3 class="mt-4">Edit Info</h3>
                <?php include_once include_path('message.php'); ?>

                <div class="card mb-4">
                <div class="card-body">
                <div class="row justify-content-center">
				  <div class="col-sm-10 justify-content-center">
					<form method="POST" action="<?php url_to('users/edit/'.$data['user']->id); ?>">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" name="user_name" value="<?php echo $data['user_name']; ?>" class="form-control form-control-lg" required>
                          <span class="error form-text"><?php echo $data['user_name_err'] ?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" readonly="" name="user_email" value="<?php echo $data['user_email']; ?>" class="form-control form-control-lg" required>
                          <span class="error form-text"><?php echo $data['user_email_err'] ?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Select Role</label>
                        <div class="col-sm-10">
                        <?php if ($data['user']->id == 1): ?>
                        	Role : Super Admin
                        	<input type="hidden" name="user_role" value="1">
                        <?php else: ?>
                        	<select name="user_role" class="custom-select" required="" >
	                            <option value="">Select Role</option>
	                            <?php
	                              foreach ($data['roles'] as $v) {
	                              	if($v->id != 1) {
	                            ?>
	                              <option value='<?php echo $v->id; ?>' <?php if($data['user_role'] == $v->id){echo 'selected';} ?> ><?php echo $v->role_name; ?></option>";
	                            <?php
	                            	}
	                              }
	                            ?>
	                          </select>
                        <?php endif ?>
                          
                          <span class="error form-text"><?php echo $data['user_role_err'] ?></span>
                        </div>
                      </div>
                     


                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                        </div>
                      </div>
                    </form>

					</div>
				  </div>

	          	</div>
	        </div>
	    </div>
	</main>








  
  <?php
    include_once include_path('footer-admin.php');
  ?>

