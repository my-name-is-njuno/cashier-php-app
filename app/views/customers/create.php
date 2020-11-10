<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');

	$scripts = ['calculate_order.js'];
?>



	<main>
            <div class="container">
            	<div class="row">
            		<div class="col-md-12">
            			<h1 class="mt-4">New Customer Order</h1>
		                
		                <ol class="breadcrumb mb-4">
		                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers') ?>">All Orders</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers/create') ?>">New Customer Order</a></li>
		                </ol>
		                <?php include_once include_path('message.php'); ?>
            		</div>
            		
            	</div>
                
                
                <div class="row">
                	<?php if (isset($data['form_err']) && !empty($data['form_err'])): ?>
                		<div class="alert alert-danger"><?php echo $data['form_err'] ?></div>
                	<?php endif ?>
                	
                </div>

                <div class="row">
                	<div class="col-md-12">
                		<div class="card">
		                	<div class="card-body">
		                		<form method="POST" action="<?php url_to('customers/create'); ?>">
		                		<div class="row">
		                			<div class="col-md-3">
		                				<div class="form-group">
											<label for="my-input">Customer Name (Optinal)</label>
											<input type="text" name="customer_name" value="<?php echo $data['customer_name']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['customer_name_err'] ?></span>
										</div>


										<div class="form-group">
											<label for="my-input">Customer Contacts (Optinal)</label>
											<input type="text" name="customer_contacts" value="<?php echo $data['customer_contacts']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['customer_contacts_err'] ?></span>
										</div>


										<div class="form-group">
											<label for="my-input">Order Type</label>
											<select name="singleorder_table" id="singleorder_table" class="custom-select" required="">
												<option value="">Choose Type</option>
												<option value="Takeaway" 
													<?php if ($data["singleorder_table"] == "Takeaway"): ?>
														<?= "selected" ?>
													<?php endif ?>>Takeaway
												</option>
												<option value="Dine In" 
													<?php if ($data["singleorder_table"] == "Dine In"): ?>
														<?= "selected" ?>
													<?php endif ?>>Dine In
												</option>
											</select>
											<span class="error form-text"><?php echo $data['customer_contacts_err'] ?></span>
										</div>
		                			</div>


		                			<div class="col-md-6">

		                				<label for="">Choose Menu Items</label>
										
										<table class="table table-responsive table-sm">
											<thead>
												<tr class="bg-light">
													<th width="30%">Item</th>
													<th width="20%">Cost</th>
													<th width="15%">Select</th>	
													<th width="15%">Quantity</th>
													<th width="20%">Total</th>
													
												</tr>
											</thead>
											<tbody>
												<?php foreach ($data['menus'] as $value): ?>
													<tr>
														<td>
															<?= $value->menu_item; ?>
														</td>
														<td>
															Kes <?= number_format($value->menu_price) ?>
															<input type="hidden" name="order_price_per_item_<?php echo $value->id;  ?>" id ="order_price_per_item_<?php echo $value->id;  ?>" value="<?= $value->menu_price ?>" >
														</td>
														<td class="center">
															<input type="checkbox" value="<?php echo $value->id;  ?>" name="menus[]" class="check_with_js form-check checkbox-2x">
														</td>
														<td>
															<input type="number" readonly name="order_quantity_<?php echo $value->id?>" value="1.00" class="form-control form-control-sm" id="order_quantity_<?php echo $value->id?>">
														</td>
														<td>
															<input type="number" readonly="" name="order_total_<?php echo $value->id?>" class="form-control form-control-sm text-right totals" id = "order_total_<?php echo $value->id?>" value = "0.00">
														</td>
														
													</tr>
												<?php endforeach ?>
												
											</tbody>
																					
										</table>
										<table class="table table-bordered">
											<tr>
													<td>
														<b>
															Total
														</b>
														<span class="float-right">Kes</span>
													</td>
													
													<td width="25%">
														<input id="receipt_total_amount" readonly type="number" value="0.00" name="receipt_total_amount" class="form-control form-control-sm text-right">
													</td>
												</tr>
										</table>

		                			</div>


		                			<div class="col-md-3">
				                      	<div class="form-group">
											<label for="my-input">Received</label>
											<input type="number" name="receipt_paid" id="receipt_paid" value="<?php echo $data['receipt_paid']; ?>" class="form-control form-control-sm" required>
											<span class="error  form-text"><?php echo $data['receipt_paid_err'] ?></span>
										</div>
				                      	

				                      	<div class="form-group">
											<label for="my-input">Balance (auto computed)</label>
											<input readonly="" type="number" name="receipt_balance" id="receipt_balance" value="<?php echo $data['receipt_balance']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['receipt_balance_err'] ?></span>
										</div>
		                			</div>
		                		</div>
		                		<div class="row">

		                			<div class="col-md-12">
		                				<hr>
		                				<button class="btn-primary btn btn-block" type="submit">Add Order</button>
		                			</div>
		                		</div>
		                	</form>
		                	</div>
		                </div>
                	</div>
                	
                </div>

                
	    </div>
	</main>










<?php 
	include_once include_path('footer.php');
?>