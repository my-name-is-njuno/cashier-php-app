<?php
include_once include_path('header-admin.php');
include_once include_path('sidenav-admin.php');
include_once include_path('topnav-admin.php');
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

                <p class="text-muted text-center"> <?= proper_case($data['user']->role_name) ?></p>
              </div>


              
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
                  
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab"><?php echo $data['user']->user_name ?> Blog Posts</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">


                  <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    
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
    include_once include_path('footer-admin.php');
  ?>