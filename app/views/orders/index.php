<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		Roles
	</h1>
	<a href="<?php url_to('roles/create'); ?>">New Role</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	
	<?php 
		foreach ($data['roles'] as $value) {
	?>
		<?php echo $value->role_name ; ?>. |  <a href = '<?php url_to('roles/edit/'.$value->id); ?>'>edit</a> | <a href = '<?php url_to('roles/show/'.$value->id); ?>'>show</a> | <a href = '<?php url_to('roles/delete/'.$value->id); ?>'>delete</a> <br>
	<?php
		}
	?>

</div>





<?php 
	include_once include_path('footer.php');
?>