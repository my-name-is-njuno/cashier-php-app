<?php 
	include_once include_path('header.php');
	include_once include_path('topnav.php');
	$scripts = ['save_menu_with_ajax.js'];
?>


				<main>
            <div class="container">
                <h1 class="mt-4"><?= APP_NAME ?></h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="<?php url_to('') ?>">Home</a></li>
                </ol>

                <?php include_once include_path('message.php'); ?>

                
                <div class="row mb-2 d-flex justify-content-center">
                    <a class="align-items-center btn btn-lg btn-primary m-1" href="<?php url_to('customers/create') ?>">New Customer Order <i class="fa fa-plus"></i></a>
                </div>
            </div>




            <section id="portfolio">
              <div class="container wow fadeInUp">
                <div class="row">

                  <div class="col-lg-12">
                    
                  </div>
                </div>

                <div class="row justify-content-center" id="portfolio-wrapper">
                  <?php foreach ($data['menus']['data'] as $v): ?>
                    <div class="col-lg-3 col-md-6 portfolio-item filter-app">
                      <a href="<?php url_to('menus/show/'.$v->id) ?>">
                        <img src="<?php echo get_img('menu/'.$v->menu_image) ?>" alt="">
                        <div class="details">
                          <h4><?php echo $v->menu_item ?></h4>
                          <span><?php echo $v->category_name ?></span>
                        </div>
                      </a>
                    </div>
                  <?php endforeach ?>
                </div>

              </div>
            </section>
        </main>











<?php 
	include_once include_path('footer.php');
?>