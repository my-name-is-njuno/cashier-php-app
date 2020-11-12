<?php
include_once include_path('header.php');
include_once include_path('sidenav.php');
include_once include_path('topnav.php');

?>



	<main>
            <div class="container">
            	<div class="row">
            		<div class="col-md-12">
            			<h1 class="mt-4"><?php $data['user']->user_name ?> Activities (<?= $data['activitys']['count']; ?>)</h1>

		                <ol class="breadcrumb mb-4">
		                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('users/settings') ?>">All Employees</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers/create') ?>"><?php $data['user']->user_name ?> Activities</a></li>
		                </ol>

            		</div>

            	</div>


				<?php include_once include_path('message.php'); ?>



                <div class="row">
                	<div class="col-sm-12">
                		<?php if (isset($data['form_err']) && !empty($data['form_err'])): ?>
	                		<div class="alert alert-danger"><?php echo $data['form_err'] ?></div>
	                	<?php endif ?>
                	</div>
                </div>

                <div class="row">
                	<div class="col-sm-12">
                		<ul class="list-group">
                			<?php foreach ($data['activitys']['data'] as $v): ?>
                				<li class="list-group-item">
                					<?= $v->activity_description ?>
                				</li>
                			<?php endforeach ?>
                		</ul>
                	</div>
                </div>


	    </div>
	</main>










	<?php
		include_once include_path('footer-admin.php');
	?>
