<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>

	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				 <h1 class="mt-4">
				 	Reports
				 </h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('reports') ?>">Reports</a></li>
                </ol>
			</div>
		</div>
		<?php include_once include_path('message.php'); ?>
		<div class="row">
			<div class="col-sm-12">
				<div class="panel-group" id="accordion">


					<div class="panel bg-white p-3 mt-3 panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> Todays Reports </a>
							</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse in">
							<div class="panel-body">
								<form action="<?php url_to('reports/today') ?>" method="post">
									<div class="row">
										
									</div>
									<div class="row">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-block btn-info">Generate Report For Today</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>


					<div class="panel panel-default bg-white p-3 mt-4">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Reports for days between Dates</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in">
							<div class="panel-body">
								<form action="<?php url_to('reports/daily') ?>" method="post">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">From</label>
												<input type="date" name="from" class="form-control" required>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<label for="">To</label>
												<input type="date" name="to" class="form-control" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-block btn-info">Generate</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>




					<div class="panel panel-default bg-white p-3 mt-4">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Monthly Reports</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse">
							<div class="panel-body">
								<form action="<?php url_to('reports/monthly') ?>" method="post">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Month</label>
												<select name="month" id="" class="custom-select">
													<option value="">Choose Month</option>
													<?php 

														for($m=1; $m<=12; ++$m){
														    echo "<option value=".date('F', mktime(0, 0, 0, $m, 1)).">".date('F', mktime(0, 0, 0, $m, 1))."</option>";
														}
													 ?>
													
													
												</select>
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Year</label>
												<select name="year" id="" class="custom-select">
													<option value="">Choose Year</option>
													<option value="2020">2020</option>
													<option value="2021">2021</option>
													<option value="2022">2022</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<button type="submit" class="btn btn-block btn-info">Generate</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					
			</div>
		</div>

		
	



	</div>
</main>




<?php 
	include_once include_path('footer.php');
?>