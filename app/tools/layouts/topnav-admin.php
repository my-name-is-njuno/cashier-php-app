<div class="main-wrapper">
  <section class="cta-section  theme-bg-light py-5">
  <div class="overlay">
    <div class="container text-center">
      <h2 class="heading">JN Media</h2>
      <div class="intro">The Accountant Who Codes. <br>
      </div>
       <div class="mt-1">
      <a class="btn btn-primary mt-1 text-white">New Blog</a>
      <?php if (get_sess('logged_in_user_id') == 1): ?>
        <a href="<?php url_to('users/create') ?>"  class="btn btn-primary mt-1 text-white">New User</a> 
      <?php endif ?>
      
       </div>
    </div><!--//container-->
  </div>
  </section>
