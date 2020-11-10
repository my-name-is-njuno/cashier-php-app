<?php 
  include_once include_path('header.php');
  include_once include_path('topnav.php');
?>



  <main>
    
  
    <section class="main">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  
                </div>

                <h3 class="profile-username text-center"><?= proper_case($data['user']->user_name) ?></h3>

                <p class="text-muted text-center"> <?= proper_case($data['role']->role_name) ?></p>
              </div>


              <ul class="list-group m-2">
                <li class="list-group-item">
                  Orders added <span class="badge badge-primary float-right"><?= $data['singleorders']['count'] ?></span>
                </li>
                <li class="list-group-item">
                  Total Receipts <span class="badge badge-primary float-right"><?= $data['total']->paid ?></span>
                </li>
              </ul>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <?php include_once include_path('message.php'); ?>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Update Info</a></li>
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Your Activities</a></li>  
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <?php foreach ($data['singleorders']['data'] as $value): ?>
                      <div class="post">
                        <div class="user-block">
                        
                          <span class="username">
                            <?= $value->customer_name ?>
                            <a href="<?php url_to('customers/show/'.$value->id) ?>" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                          </span>
                          <span class="description">Added at <?php echo formatedDate($value->sigleorder_at); ?></span>
                        </div>
                        <!-- /.user-block -->
                                          
                      </div>
                    <?php endforeach ?>
                    
                    
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->









                  
                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <form class="form-horizontal" method="post" action="<?php url_to('users/edit/'.$data['user']->id) ?>">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Employee Name</label>
                        <div class="col-sm-10">
                          <input type="text" name="user_name" value="<?php echo $data['user']->user_name; ?>" class="form-control" required>
                          <span class="error form-text"><?php echo $data['user_name_err'] ?></span>
                        </div>
                      </div>
                      
                      <div class="form-group row">
                        <label for="inputExperience" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10">
                          <input type="password" name="user_password" value="<?php echo $data['user_password']; ?>" required class="form-control">
                          <span class="error form-text"><?php echo $data['user_password_err'] ?></span>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputSkills" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                          <input type="password" name="user_confirm_password" value="<?php echo $data['user_confirm_password']; ?>" required class="form-control">
                          <span class="error form-text"><?php echo $data['user_confirm_password_err'] ?></span>
                        </div>
                      </div>
                     
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-info">Update Password</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>


  </main>






<?php 
  include_once include_path('footer.php');
?>