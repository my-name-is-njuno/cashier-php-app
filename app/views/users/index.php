<?php
include_once include_path('header-admin.php');
include_once include_path('sidenav-admin.php');
include_once include_path('topnav-admin.php');
?>




<?php
	include_once include_path('footer-admin.php');
?>





<article class="blog-post px-3 py-5 p-md-5">
	<div class="container">
		<h2 class="title mb-2">Users</h2>
		<table	class="table dt">
			<thead>
				<tr>
					<th width="50%">
						Name
					</th>
					<th width="20%">
						Role
					</th>
					<th>
						Actions
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data['users'] as $value): ?>
					<tr>
						<td>
							<?= $value->user_name ?>
						</td>
						<td>
							<?= $value->role_name ?>
						</td>
						<td>
							<?php if ($data['user']->id == 1): ?>
								<a href="<?php url_to('users/edit/'.$value->id) ?>" class="px-1">edit</a>
								<a href="<?php url_to('users/delete/'.$value->id) ?>" class="px-1">delete</a>
							<?php endif ?>
							
							<a href="<?php url_to('users/show/'.$value->id) ?>" class="px-1">show</a>
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>

</article>




<?php
include_once include_path('footer-admin.php');
?>