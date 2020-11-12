<?php
include_once include_path('header-admin.php');
include_once include_path('sidenav-admin.php');
include_once include_path('topnav-admin.php');
?>

	<main>
            <div class="container">
                <h3 class="mt-4">Add New User</h3>
                <?php include_once include_path('message.php'); ?>

                <div class="card mb-4">
                <div class="card-body">
                <div class="row justify-content-center">
				  <div class="col-sm-10 justify-content-center">
					<form class="form-horizontal" method="post" action="<?php url_to('users/create') ?>">
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
                          <input type="email" name="user_email" value="<?php echo $data['user_email']; ?>" class="form-control form-control-lg" required>
                          <span class="error form-text"><?php echo $data['user_email_err'] ?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Select Role</label>
                        <div class="col-sm-10">
                          <select name="user_role" class="custom-select" required="">
                            <option value="">Select Role</option>
                            <?php
                              foreach ($data['roles']['data'] as $v) {
                              	if($v->id != 1) {
                            ?>
                              <option value='<?php echo $v->id; ?>' <?php if($data['user_role'] == $v->id){echo 'selected';} ?> ><?php echo $v->role_name; ?></option>";
                            <?php
                            	}
                              }
                            ?>
                          </select>
                          <span class="error form-text"><?php echo $data['user_role_err'] ?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                          <input type="password" readonly="" name="user_password" value="password" required class="form-control form-control-lg">
                          <span class="error form-text">New Users Passwords is set to <b>password</b>. They will have change it once they login.</span>
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
