<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>

<center>
	<h1>
		Users - Edit
	</h1>
	<?php 
		include_layouts('message.php');
	?>
</center>

<hr>


<div class="main">
	<form method="POST" action="<?php url_to('users/edit/'.$data['user']->id); ?>">
		<table>
			<tr>
				<td>
					Name
				</td>
				<td>
					<input type="text" name="user_name" value="<?php echo $data['user_name']; ?>">
				</td>
				<td>
					<span class="error"><?php echo $data['user_name_err'] ?></span>
				</td>
			</tr>
			<tr>
				<td>
					Email
				</td>
				<td>
					<input type="email" name="user_email" value="<?php echo $data['user_email']; ?>">
				</td>
				<td>
					<span class="error"><?php echo $data['user_email_err'] ?></span>
				</td>
			</tr>
			

			


			


			<tr>
				<td>
					Role
				</td>
				<td>
					<select name="user_role">
						<option value="" >Select Role</option>
						<?php 
							foreach ($data['roles'] as $v) {
						?>
							<option value='<?php echo $v->id; ?>' <?php if($data['user_role'] == $v->id){echo 'selected';} ?> ><?php echo $v->role_name; ?></option>";
						<?php 
							}
						?>
					</select>
				</td>
				<td>
					<span class="error"><?php echo $data['user_role_err'] ?></span>
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