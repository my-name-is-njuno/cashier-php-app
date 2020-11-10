<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		receipts Profile - <?php echo $data['receipt']->receipt_name; ?>
	</h1>
	<a href="<?php url_to('receipts/create'); ?>">New receipt</a>
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