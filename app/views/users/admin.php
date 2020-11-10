<?php 
  include_once include_path('header.php');
  include_once include_path('topnav.php');
?>



  <main>
    
  
    <section class="main">
      <div class="container">
        <h1 class="mt-4">Admin Settings</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php url_to('users/settings') ?>">Admin Panel</a></li>
        </ol>
        
    </div>

      <div class="container">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                

                <h3 class="profile-username text-center"><?= proper_case($data['user']->user_name) ?></h3>

                <p class="text-muted text-center">Admin</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Employees</b> <a class="float-right"><?= $data['users']['count']-1 ?></a>
                  </li>
                  
                </ul>

                
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <?php include_once include_path('message.php'); ?>
            <?php if (isset($data['form_err']) && !empty($data['form_err'])): ?>
              <div class="alert alert-danger"><?php echo $data['form_err'] ?></div>
            <?php endif ?>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Your Employes</a></li>
                  <li class="nav-item"><a class="nav-link" href="#menus" data-toggle="tab">Our Menus</a></li>                                  
                  <!-- <li class="nav-item"><a class="nav-link" href="#categorys" data-toggle="tab">Menu Categorys</a></li> -->
                  
                  
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">


                <div class="tab-content">
                  <div class="active tab-pane bg-light p-2 m-1" id="activity">
                    
                    <div class="row mb-2 d-flex justify-content-end">
                        <a class="align-item-end btn btn-primary m-1" href="<?php url_to('users/create') ?>">New Employee <i class="fa fa-plus"></i></a>
                        <a class="align-item-end btn btn-dark m-1" href="<?php url_to('roles/create') ?>">New Employee Role <i class="fa fa-plus"></i></a>
                    </div>


                    <table class="table table-sm table-bordered">
                      <thead>
                        <tr>
                          <th width="30%">
                            Name
                          </th>
                          <th width="7%">
                            Status
                          </th>
                          <th width="7%">
                            Role 
                          </th>
                          <th width="15%">
                            Joined On
                          </th>
                          <th>
                            Activities
                          </th>
                          <th>
                            Deactivate
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($data['users']['data'] as $user): ?>
                          <?php if ($user->id != $data['user']->id): ?>
                            <tr>
                              <td>
                                <?= $user->user_name ?>
                              </td>
                              <td>
                                <?php if ($user->user_active): ?>
                                  <span class="btn btn-primary btn-sm"><i class="fa fa-check"></i></span><?php else: ?>
                                  <span class="btn btn-red btn-sm"><i class="fa fa-times"></i></span>
                                <?php endif ?>
                              </td>
                              <td>
                                <a href="<?php url_to('roles/show/'.$user->rid) ?>"><?= $user->role_name; ?></a>
                              </td>
                              <td>
                                <?= formatedDateshow($user->user_at) ?>
                              </td>
                              <td>
                                <a href="<?php url_to('users/activitys/'.$user->id) ?>" class=""><?= $user->cnt ?> Activities</a>
                              </td>
                              <td>
                                <?php if ($user->user_active): ?>
                                  <a href="<?php url_to('users/activate/'.$user->id) ?>" class="btn btn-sm btn-danger">Deactivate User</a>
                                <?php else: ?>
                                  <a href="<?php url_to('users/activate/'.$user->id) ?>" class="btn btn-sm btn-info">Activate User</a>
                                <?php endif ?>
                                
                              </td>
                            </tr>
                          <?php endif ?>
                            
                        <?php endforeach ?>
                      </tbody>
                    </table>
                    
                    

                   


                  </div>




                 













             
                  <!-- /.tab-pane -->












                   



































                  <div class="tab-pane" id="menus">

                    <div class="bg-light mb-2 p-2">
                      
                      <div class="row mb-2 d-flex justify-content-end">
                          <a class="align-item-end btn btn-primary m-1" href="<?php url_to('menus/create') ?>">New Menu Item <i class="fa fa-plus"></i></a>
                          <a class="align-item-end btn btn-dark m-1" href="<?php url_to('categorys/create') ?>">New Menu Category <i class="fa fa-plus"></i></a>
                      </div>
                      <table class="table table table-bordered">
                        <thead>
                          <tr>
                            <th width="40%">
                              Menu Item
                            </th>
                            <th width="20%">
                              Menu Cost
                            </th>
                            <th width="20%">
                              S. Price
                            </th>
                            <th>
                              Actions
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($data['menus']['data'] as $v): ?>
                            <tr>
                              <td>
                                <?= $v->menu_item ?>
                              </td>
                              <td class="text-right">
                                Kes: <?= number_format($v->menu_cost) ?>
                              </td>
                              <td class="text-right"> 
                                Kes <?= number_format($v->menu_price) ?>
                              </td>
                              <td>
                                <a href="<?php url_to('menus/show/'.$v->id) ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                <a href="<?php url_to('menus/edit/'.$v->id) ?>" class="btn btn-dark btn-sm"><i class="fa fa-edit"></i></a>
                                <a href="<?php url_to('menus/delete/'.$v->id) ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                              </td>

                            </tr>
                          <?php endforeach ?>
                        </tbody>
                      </table>
                    </div>
                    
                  
                    

                   


                </div>
                <!-- /.tab-content -->





















                <div class="tab-pane" id="newmenus">

                    
                 
                      
                    
                  
                    

                    <h4>Add New Menu Item</h4>
                    <form class="form-horizontal" method="post" action="<?php url_to('users/menucreate') ?>">
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Menu Item</label>
                        <div class="col-sm-10">
                          <input type="text" name="menu_item" class="form-control" value="<?php echo $data['menu_item']; ?>" required>
                          <span class="form-text text-danger"><?php echo $data['menu_item_err'] ?></span>
                        </div>
                      </div>
                      

                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Short Description</label>
                        <div class="col-sm-10">
                          <textarea type="text" name="menu_description" class="form-control" required><?php echo $data['menu_description']; ?></textarea>
                          <span class="form-text text-danger"><?php echo $data['menu_item_err'] ?></span>
                        </div>
                      </div>



                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Cost</label>
                        <div class="col-sm-10">
                          <input type="number" name="menu_cost" class="form-control" value="<?php echo $data['menu_cost']; ?>" required>
                        <span class="form-text text-danger"><?php echo $data['menu_cost_err'] ?></span>
                        </div>
                      </div>







                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Selling Price</label>
                        <div class="col-sm-10">
                          <input type="number" name="menu_price" class="form-control" value="<?php echo $data['menu_price']; ?>" required>
                          <span class="form-text text-danger"><?php echo $data['menu_price_err'] ?></span>
                        </div>
                      </div>



                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Select Category</label>
                        <div class="col-sm-10">
                          <select id="my-select" class="custom-select" name="menu_category_id">
                            <option value="">Choose Category</option>
                            <?php foreach ($data['categorys'] as $v): ?>
                              <option value="<?= $v->id ?>" <?php if ($v->id == $data['menu_category_id']): echo 'selected'; ?><?php endif ?>
                              ><?= $v->category_name ?></option>
                            <?php endforeach ?>
                          </select>
                          <span class="form-text text-danger"><?php echo $data['menu_category_id_err'] ?></span>
                        </div>
                      </div>




                     


                      
                     
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>

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