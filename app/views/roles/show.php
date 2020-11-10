<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

	




	<main>
		
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h1>
						<?= $data['role']->role_name ?> Employee(s) (<?= $data['users']['count'] ?>)
					</h1>
				</div>
			</div>
			<?php include_once include_path('message.php'); ?>
			<div class="row">
				<div class="col-sm-12">
					<ol class="breadcrumb mb-4">
	                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
	                    <li class="breadcrumb-item"><a href="<?php url_to('roles') ?>">Employees Roles</a></li>
	                    <li class="breadcrumb-item"><a href="<?php url_to('roles/show/'.$data['role']->id) ?>">All <?= $data['role']->role_name ?> Employees</a></li>
	                </ol>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					
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
                                <a href="<?php url_to('users/activitys/'.$user->id) ?>" class="btn btn-success btn-sm"><?= $user->cnt ?> Activities</a>
                              </td>
                              <td>
                                <?php if ($user->user_active): ?>
                                  <a href="<?php url_to('users/deactivate/'.$user->id) ?>" class="btn btn-sm btn-danger">Deactivate User</a>
                                <?php else: ?>
                                  <a href="<?php url_to('users/deactivate/'.$user->id) ?>" class="btn btn-sm btn-info">Activate User</a>
                                <?php endif ?>
                                
                              </td>
                            </tr>
                        
                            
                        <?php endforeach ?>
                      </tbody>
                    </table>
				</div>
			</div>
		</div>
	</main>






<?php 
	include_once include_path('footer.php');
?>