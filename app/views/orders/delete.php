<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		Roles Delete - <?php echo $data['rol']->role_name; ?>
	</h1>
	<a href="<?php url_to('roles/create'); ?>">New Role</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<center>
		<form class="" method="post" action="<?php url_to('roles/delete/'.$data['rol']->id); ?>">
		<p>Are You sure you want to delete this role??</p>
		<button type="submit">Yes, Delete role please</button>
	</form>
	<button><a href="<?php url_to('roles/show/'.$data['rol']->id);?>">No, Take me back</a></button>
	</center>
	
</div>





<?php 
	include_once include_path('footer.php');
?>