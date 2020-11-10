<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');

	$scripts = ['calculate_balance.js'];
?>



	<main>
            <div class="container">
            	<div class="row">
            		<div class="col-md-12">
            			<h1 class="mt-4">Receive Balance</h1>
		                
		                <ol class="breadcrumb mb-4">
		                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers') ?>">All Orders</a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers/show/'.$data['singleorder']->id) ?>">Customer Order <?php echo $data['singleorder']->id ?></a></li>
		                    <li class="breadcrumb-item"><a href="<?php url_to('customers/receivebalance/'.$data['singleorder']->id) ?>">Receive Balance</a></li>
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
		                		<form method="POST" action="<?php url_to('customers/recievebalance/'.$data['singleorder']->id); ?>">
		                		<div class="row">
		                			<div class="col-md-3">
		                				<div class="form-group">
											<label for="my-input">Customer Name (Optinal)</label>
											<input readonly="" type="text" name="customer_name" value="<?php echo $data['customer_name']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['customer_name_err'] ?></span>
										</div>


										<div class="form-group">
											<label for="my-input">Customer Contacts (Optinal)</label>
											<input readonly type="text" name="customer_contacts" value="<?php echo $data['customer_contacts']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['customer_contacts_err'] ?></span>
										</div>


										<div class="form-group">
											<label for="my-input">Order Type</label>
											<select name="singleorder_table" disabled="" id="singleorder_table" class="custom-select" required="">
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
												<?php foreach ($data['order_menus']['data'] as $value): ?>
													<tr>
														<td>
															<?= $value->menu_item; ?>
														</td>
														<td>
															Kes <?= number_format($value->menu_price) ?>
															<input type="hidden" name="order_price_per_item_<?php echo $value->id;  ?>" id ="order_price_per_item_<?php echo $value->id;  ?>" value="<?= $value->order_price_per_item ?>" >
														</td>
														<td class="center">
															<input type="checkbox" readonly="" value="<?php echo $value->id;  ?>" checked disabled name="menus[]" class=" form-check checkbox-2x">
														</td>
														<td>
															<input type="number" readonly name="order_quantity_<?php echo $value->id?>" value="<?= $value->order_quantity ?>" class="form-control form-control-sm" id="order_quantity_<?php echo $value->id?>">
														</td>
														<td>
															<input type="number" readonly="" name="order_total_<?php echo $value->id?>" class="form-control form-control-sm text-right totals" id = "order_total_<?php echo $value->id?>" value="<?= $value->order_total ?>">
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
													<input type="hidden" value="<?= $data['receipt']->receipt_balance ?>" id="receipt_remaining">
													<td width="25%">
														<input id="receipt_total_amount" readonly type="number" value="<?= $data['receipt']->receipt_total_amount ?>" name="receipt_total_amount" class="form-control form-control-sm text-right">
													</td>
												</tr>
										</table>

		                			</div>


		                			<div class="col-md-3">

		                				<div class="form-group">
											<label for="my-input">Paid</label>
											<input type="hidden" value="<?php echo $data['receipt_paid']; ?>" id="pd">
											<input type="number" name="receipt_paid" id="receipt_paid" value="<?php echo $data['receipt_paid']; ?>" readonly class="form-control form-control-sm" required>
											<span class="error  form-text"><?php echo $data['receipt_paid_err'] ?></span>
										</div>


										<div class="form-group">
											<label for="my-input">Balance (auto computed)</label>
											<input readonly="" type="number" name="receipt_balance" id="receipt_balance" value="<?php echo $data['receipt_balance']; ?>" class="form-control form-control-sm">
											<span class="error form-text"><?php echo $data['receipt_balance_err'] ?></span>
										</div>


				                      	<div class="form-group">
											<label for="my-input">Received</label>
											<input type="number" name="received" id="received" value="<?php echo $data['received']; ?>" class="form-control form-control-sm" required>
											<span class="error  form-text"><?php echo $data['received_err'] ?></span>
										</div>
				                      	

				                      	
		                			</div>
		                		</div>
		                		<div class="row">

		                			<div class="col-md-12">
		                				<hr>
		                				<button class="btn-primary btn btn-block" type="submit">Receive Balance Payment</button>
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