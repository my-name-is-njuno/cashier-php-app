<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		receipts Delete - <?php echo $data['rol']->receipt_name; ?>
	</h1>
	<a href="<?php url_to('receipts/create'); ?>">New receipt</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<center>
		<form class="" method="post" action="<?php url_to('receipts/delete/'.$data['rol']->id); ?>">
		<p>Are You sure you want to delete this receipt??</p>
		<button type="submit">Yes, Delete receipt please</button>
	</form>
	<button><a href="<?php url_to('receipts/show/'.$data['rol']->id);?>">No, Take me back</a></button>
	</center>
	
</div>





<?php 
	include_once include_path('footer.php');
?>