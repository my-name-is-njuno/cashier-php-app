<?php 
  $scripts = ['jspdf.min.js', 'htmlcanvas.js', 'receipt_to_pdf.js'];
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>



<main>
	
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h1 class="mt-4">Customer Order</h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('customers') ?>">All Orders</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('customers/show/'.$data['singleorder']->id) ?>">Customer Order  <?= $data['singleorder']->id; ?></a></li>
                </ol>

                <?php include_once include_path('message.php'); ?>


            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row mb-3">
                <div class="col-12">
                  <h4>
                    Francis Cafe.
                    <small class="float-right">Date: <?php echo formatedDateshow($data['singleorder']->singleorder_at) ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Order From
                  <address>
                    Name: 
                  	<strong>
                  		<?php if ($data['customer']->customer_name == 'Customer'): ?>
                  			<?= $data['customer']->customer_name . " - ". $data['customer']->id ?> 
                  		<?php else: ?>
                  			<?= $data['customer']->customer_name ?>
                  		<?php endif ?>
                  		
                  	</strong><br>
                    Contacts: <b><?= $data['customer']->customer_contacts ?></b><br>
                    
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    <strong>Francis Cafe.</strong><br>
                    Meru<br>
                    Phone: 071445215<br>
                    Email: franciscafe@gmail.com
                    
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <b>Order No. <?= $data['singleorder']->id ?></b><br>
                  <br>
                  <table class="table">
                  	<tr>
                  		<th>
                  			Total Amount
                  		</th>
                  		<td>
                  			Kes <?= number_format($data['singleorder']->singleorder_total) ?>
                  		</td>
                  	</tr>
                  	<tr>
                  		<th>
                  			Paid
                  		</th>
                  		<td>
                  			Kes <?= number_format($data['receipt']->receipt_paid) ?>
                  		</td>
                  	</tr>
                  	<tr>
                  		<th>
                  			Balance
                  		</th>
                  		<td>
                  			Kes 
                        <?php if ($data['receipt']->receipt_balance > 0): ?>
                          <a class="bg-danger btn text-white" href="<?php url_to('customers/recievebalance/'.$data['singleorder']->id) ?>"><?= number_format($data['receipt']->receipt_balance) ?></a> 
                        <?php endif ?>
                         
                  		</td>
                  	</tr>
                  </table>
                  
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      
                      <th>Menu Item</th>
                      <th>Price Per</th>
                      <th>Qty</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    	<?php foreach ($data['orders']['data'] as $v): ?>
                    		<tr>
		                      <td><?= $v->menu_item; ?></td>
		                      <td><?= number_format($v->order_price_per_item); ?></td>
		                      <td><?= $v->order_quantity; ?></td>
		                      <td>Kes <?= number_format($v->order_total) ?></td>
		                    </tr>
                    	<?php endforeach ?>
                    
                    
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Payment Methods:</p>
                  
                  <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    Thank you for being our customers, may God bless you
                  </p>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              
            </div>
              <div class="row no-print">
                <div class="col-12">


                  <a href="<?php url_to('reports/receipt/'.$data['singleorder']->id) ?>" class="btn btn-primary float-right" style="margin-right: 5px;" id="pdf">
                    <i class="fas fa-download"></i> Generate PDF
                  </a>
                  
                  <?php if ($data['receipt']->receipt_balance > 0): ?>
                    <a href="<?php url_to('customers/recievebalance/'.$data['singleorder']->id) ?>" class="btn btn-dark float-right mr-2">Receive Balance</a>
                  <?php endif ?>


                    <a href="<?php url_to('customers/edit/'.$data['singleorder']->id) ?>" class="btn btn-info float-right mr-2"><i class="fa fa-edit"></i></a>
                 
                  
                  
                  

                  <input type="hidden" value="<?= $data['receipt']->id ?>" id = "receipt_no">
                </div>
              </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->

</main>








<?php 
	include_once include_path('footer.php');
?>