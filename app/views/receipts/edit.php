<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		receipts - Edit
	</h1>
	<a href="<?php url_to('receipts/create'); ?>">New receipt</a>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<form method="POST" action="<?php url_to('receipts/edit/'.$data['receipt']->id); ?>">
		<table>
			<tr>
				<td>
					Name
				</td>
				<td>
					<input type="text" name="receipt_name" value="<?php echo $data['receipt_name']; ?>">
				</td>
				<td>
					<span class="error"><?php echo $data['receipt_name_err'] ?></span>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button type="submit">Submit</button>
				</td>
			</tr>
		</table>
	</form>

</div>








<?php 
	include_once include_path('footer.php');
?>