<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		Roles Profile - <?php echo $data['role']->role_name; ?>
	</h1>
	<a href="<?php url_to('roles/create'); ?>">New Role</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	
</div>








<?php 
	include_once include_path('footer.php');
?>