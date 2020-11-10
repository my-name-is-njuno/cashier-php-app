<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		customers Delete - <?php echo $data['rol']->customer_name; ?>
	</h1>
	<a href="<?php url_to('customers/create'); ?>">New customer</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<center>
		<form class="" method="post" action="<?php url_to('customers/delete/'.$data['rol']->id); ?>">
		<p>Are You sure you want to delete this customer??</p>
		<button type="submit">Yes, Delete customer please</button>
	</form>
	<button><a href="<?php url_to('customers/show/'.$data['rol']->id);?>">No, Take me back</a></button>
	</center>
	
</div>





<?php 
	include_once include_path('footer.php');
?>