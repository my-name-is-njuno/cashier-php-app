<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
?>




<main>
            <div class="container">
                <h1 class="mt-4"><?= $data['menu']->menu_item ?></h1>
                
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('menus') ?>">Our Menu</a></li>
                    <li class="breadcrumb-item"><a href="<?php url_to('menus/show/'.$data['menu']->id) ?>"><?= $data['menu']->menu_item ?> Info</a></li>
                </ol>
                <?php include_once include_path('message.php'); ?>
                <div class="card mb-4">
                <div class="card-body">
                  
				<div class="row justify-content-center">
					
				  <div class="col-sm-5">
                      <div class="card">
                      	<div class="card m-4">
                          <img class="card-img-top" src="<?php echo get_img('menu/'.$data['menu']->menu_image) ?>" alt="Card image cap">
                          <div class="card-body">
                            <h5 class="card-title"><?php echo $data['menu']->menu_item; ?></h5>
                            <p class="card-text">
                              <?= $data['menu']->menu_description ?> ...
                            </p>
                            <p>
                              <b>Price: </b> Kes <?= number_format($data['menu']->menu_price) ?>
                            </p>
                          </div>
                          <div class="card-footer text-center">
                            <a href="<?php url_to('menus/edit/'.$data['menu']->id)?>" class="btn btn-primary btn-sm m-1"><i class="fas fa-edit    "></i></a>
                            <?php if (!$data['sales']->orders > 0): ?>
                              <a href="<?php url_to('menus/delete/'.$data['menu']->id)?>" class="btn btn-danger btn-sm m-1"><i class="fas fa-trash" aria-hidden="true"></i></a>
                            <?php endif ?>
                        
                            
                          </div>
                        </div>
                      </div>
	              </div>


	              <div class="col-sm-6">
						<h4>Sales</h4>
						<table class="table">
                 <tr>
                   <th>
                     Price 
                   </th>
                   <td>
                     Kes <?= number_format($data['menu']->menu_price) ?>
                   </td>
                 </tr> 
                 <tr>
                   <th>
                     Cost
                   </th>
                   <td>
                     Kes <?= number_format($data['menu']->menu_cost) ?>
                   </td>
                 </tr>   
                 <tr>
                   <th>
                     Total Sales
                   </th>
                   <td>
                     Kes <?= number_format($data['sales']->totals) ?>
                   </td>
                 </tr> 
                 <tr>
                   <th>
                     Total Orders
                   </th>
                   <td>
                      <h4><?= number_format($data['customers']['count']) ?> Orders</h4>
                       <table class="table table-sm">
                        <ul class="list-group">
                          <?php foreach ($data['customers']['data'] as $value): ?>
                           <li class="list-group-item">
                              <a href=""><?= $value->customer_name ?> (Amount - Kes <?= number_format($value->order_total) ?>)</a>
                           </li>
                          <?php endforeach ?>
                        </ul>
                       </table>
                   </td>
                 </tr>  
            </table>
					</div>
				  </div>
                  
	          	</div>
	        </div>
	    </div>
	</main>








<?php 
	include_once include_path('footer.php');
?>