<?php
include_once include_path('header.php');
include_once include_path('sidenav.php');
include_once include_path('topnav.php');
?>

<center>
	<h1>
		Users Delete - <?php echo $data['user']->user_name; ?>
	</h1>
	<?php
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<center>
		<form class="" method="post" action="<?php url_to('users/delete/'.$data['user']->id); ?>">
		<p>Are You sure you want to delete this user??</p>
		<button type="submit">Yes, Delete user please</button>
	</form>
	<button><a href="<?php url_to('users/show/'.$data['user']->id);?>">No, Take me back</a></button>
	</center>

</div>





<?php
	include_once include_path('footer-admin.php');
?>
